<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * sfadvanced:fast-install task. enables one-liner install.
 *
 * @auther Hiromi Hishida <info@77-web.com>
 */

class sfadvancedFastInstallTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'sfadvanced';
    $this->name             = 'fast-install';

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', null),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('dbms', null, sfCommandOption::PARAMETER_OPTIONAL, 'The dbms for database connection. mysql or sqlite', 'mysql'),
      new sfCommandOption('dbuser', null, sfCommandOption::PARAMETER_OPTIONAL, 'A username for database connection.'),
      new sfCommandOption('dbpassword', null, sfCommandOption::PARAMETER_OPTIONAL, 'A password for database connection.'),
      new sfCommandOption('dbhost', null, sfCommandOption::PARAMETER_OPTIONAL, 'A hostname for database connection.'),
      new sfCommandOption('dbport', null, sfCommandOption::PARAMETER_OPTIONAL, 'A port number for database conection.'),
      new sfCommandOption('dbname', null, sfCommandOption::PARAMETER_REQUIRED, 'A database name for database connection.'),
      new sfCommandOption('dbsock', null, sfCommandOption::PARAMETER_OPTIONAL, 'A database socket path for database connection.'),
      new sfCommandOption('internet', null, sfCommandOption::PARAMETER_NONE, 'Connect Internet Option to download plugins list.'),
    ));

    $this->briefDescription = 'Install SfAdvanced';
    $this->detailedDescription = <<<EOF
The [sfadvanced:fast-install] task installs and configures SfAdvanced.
Call it with:

  [./symfony sfadvanced:fast-install --dbms=mysql --dbuser=your-username --dbpassword=your-password --dbname=your-dbname --dbhost=localhost --internet]

EOF;
  }

  protected function execute($arguments = array(), $sations = array())
  {
    $dbms = $sations['dbms'];
    $username = $sations['dbuser'];
    $password = $sations['dbpassword'];
    $hostname = $sations['dbhost'];
    $dbname = $sations['dbname'];
    $port = $sations['dbport'];
    $sock = $sations['dbsock'];
    
    if (empty($dbms))
    {
      $this->logSection('installer', 'task aborted: empty dbms');

      return 1;
    }

    if (empty($dbname))
    {
      $this->logSection('installer', 'task aborted: empty dbname');

      return 1;
    }

    if ('sqlite' !== $dbms)
    {
      if(empty($username))
      {
        $this->logSection('installer', 'task aborted: dbuser is empty');

        return 1;
      }
      
      if(empty($hostname))
      {
        $hostname = '127.0.0.1';
      }
    }
    else
    {
      $dbname = realpath(dirname($dbname)).DIRECTORY_SEPARATOR.basename($dbname);
    }
    
    unset($sations['dbms'], $sations['dbuser'], $sations['dbpassword'], $sations['dbname'], $sations['dbhost'], $sations['dbport'], $sations['dbsock']);
    $this->doInstall($dbms, $username, $password, $hostname, $port, $dbname, $sock, $sations);

    if ('sqlite' === $dbms)
    {
      $this->getFilesystem()->chmod($dbname, 0666);
    }

    $this->publishAssets();

    // _PEAR_call_destructors() causes an E_STRICT error
    error_reporting(error_reporting() & ~E_STRICT);

    $this->logSection('installer', 'installation is completed!');
  }

  protected function doInstall($dbms, $username, $password, $hostname, $port, $dbname, $sock, $sations)
  {
    if ($sations['internet'])
    {
      $this->installPlugins();
    }
    else
    {
      new saPluginManager($this->dispatcher, null, null);
    }
    @$this->fixPerms();
    @$this->clearCache();
    $this->configureDatabase($dbms, $username, $password, $hostname, $port, $dbname, $sock, $sations);
    $this->buildDb($sations);
  }

  protected function createDSN($dbms, $hostname, $port, $dbname, $sock)
  {
    $result = $dbms.':';

    $data = array();

    if ($dbname)
    {
      if ('sqlite' === $dbms)
      {
        $data[] = $dbname;
      }
      else
      {
        $data[] = 'dbname='.$dbname;
      }
    }

    if ($hostname)
    {
      $data[] = 'host='.$hostname;
    }

    if ($port)
    {
      $data[] = 'port='.$port;
    }

    if ($sock)
    {
      $data[] = 'unix_socket='.$sock;
    }

    $result .= implode(';', $data);

    return $result;
  }

  protected function configureDatabase($dbms, $username, $password, $hostname, $port, $dbname, $sock, $sations)
  {
    $dsn = $this->createDSN($dbms, $hostname, $port, $dbname, $sock);

    $file = sfConfig::get('sf_config_dir').'/databases.yml';
    $config = array();

    if (file_exists($file))
    {
      $config = sfYaml::load($file);
    }

    $env = 'all';
    if ('prod' !== $sations['env'])
    {
      $env = $sations['env'];
    }

    $config[$env]['doctrine'] = array(
      'class' => 'sfDoctrineDatabase',
      'param' => array(
        'dsn'        => $dsn,
        'username'   => $username,
        'encoding'   => 'utf8',
        'attributes' => array(
           Doctrine::ATTR_USE_DQL_CALLBACKS => true,
        ),
      ),
    );

    if ($password)
    {
      $config[$env]['doctrine']['param']['password'] = $password;
    }

    file_put_contents($file, sfYaml::dump($config, 4));
  }

  protected function clearCache()
  {
    $cc = new sfCacheClearTask($this->dispatcher, $this->formatter);
    $cc->run();
  }

  protected function publishAssets()
  {
    $publishAssets = new sfPluginPublishAssetsTask($this->dispatcher, $this->formatter);
    $publishAssets->run();
  }

  protected function buildDb($sations)
  {
    $tmpdir = sfConfig::get('sf_data_dir').'/fixtures_tmp';
    $this->getFilesystem()->mkdirs($tmpdir);
    $this->getFilesystem()->remove(sfFinder::type('file')->in(array($tmpdir)));

    $pluginDirs = sfFinder::type('dir')->name('data')->in(sfFinder::type('dir')->name('op*Plugin')->maxdepth(1)->in(sfConfig::get('sf_plugins_dir')));
    $fixturesDirs = sfFinder::type('dir')->name('fixtures')
      ->prune('migrations', 'upgrade')
      ->in(array_merge(array(sfConfig::get('sf_data_dir')), $this->configuration->getPluginSubPaths('/data'), $pluginDirs));
    $i = 0;
    foreach ($fixturesDirs as $fixturesDir)
    {
      $files = sfFinder::type('file')->name('*.yml')->sort_by_name()->in(array($fixturesDir));
      
      foreach ($files as $file)
      {
        $this->getFilesystem()->copy($file, $tmpdir.'/'.sprintf('%03d_%s_%s.yml', $i, basename($file, '.yml'), md5(uniqid(rand(), true))));
      }
      $i++;
    }

    $task = new sfDoctrineBuildTask($this->dispatcher, $this->formatter);
    $task->setCommandApplication($this->commandApplication);
    $task->setConfiguration($this->configuration);
    $task->run(array(), array(
      'no-confirmation' => true,
      'db'              => true,
      'model'           => true,
      'forms'           => true,
      'filters'         => true,
      'sql'             => true,
      'and-load'        => $tmpdir,
      'application'     => $sations['application'],
      'env'             => $sations['env'],
    ));

    $this->getFilesystem()->remove(sfFinder::type('file')->in(array($tmpdir)));
    $this->getFilesystem()->remove($tmpdir);
  }

  protected function installPlugins()
  {
    $task = new saPluginSyncTask($this->dispatcher, $this->formatter);
    $task->run();
  }

  protected function fixPerms()
  {
    $permissions = new sfadvancedPermissionTask($this->dispatcher, $this->formatter);
    $permissions->run();
  }

}
