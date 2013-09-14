<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class saPluginSyncTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'saPlugin';
    $this->name             = 'sync';

    $this->addOptions(array(
      new sfCommandOption('target', null, sfCommandOption::PARAMETER_OPTIONAL, 'The target of sync'),
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', null),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
    ));

    $this->briefDescription = 'Synchronize bandled plugins';
    $this->detailedDescription = <<<EOF
The [saPlugin:sync|INFO] task synchronizes all bandled plugins.
Call it with:

  [php symfony saPlugin:sync|INFO]
EOF;
  }

  protected function execute($arguments = array(), $sations = array())
  {
    require sfConfig::get('sf_data_dir').'/version.php';
    
    sfToolkit::addIncludePath(array(sfConfig::get('sf_lib_dir').'/vendor/'));

    $pluginList = $this->getPluginList();
    foreach ($pluginList as $name => $info)
    {
      if ($sations['target'] && $name !== $sations['target'])
      {
        continue;
      }

      if (!preg_match('/^op[a-zA-Z0-9_\-]+Plugin$/', $name))
      {
        continue;
      }

      if (isset($info['install']) && false === $info['install'])
      {
        continue;
      }

      $sation = array();
      if (isset($info['version']))
      {
        $sation[] = '--release='.$info['version'];
      }
      if (isset($info['channel']))
      {
        $sation[] = '--channel='.$info['channel'];
      }
      try
      {
        $task = new saPluginInstallTask($this->dispatcher, $this->formatter);
        $task->run(array('name' => $name), $sation);
      }
      catch (sfCommandException $e)
      {
        $str = "Failed install";
        $this->logBlock($str, 'ERROR');
      }
    }
  }

  protected function getPluginList()
  {
    $list = array();

    $config = null;

    if ($proxy = parse_url(sfConfig::get('sa_http_proxy')))
    {
      $config = array('adapter' => 'Zend_Http_Client_Adapter_Proxy');

      if (isset($proxy['host']))
      {
        $config['proxy_host'] = $proxy['host'];
      }

      if (isset($proxy['port']))
      {
        $config['proxy_port'] = $proxy['port'];
      }

      if (isset($proxy['user']))
      {
        $config['proxy_user'] = $proxy['user'];
      }

      if (isset($proxy['pass']))
      {
        $config['proxy_pass'] = $proxy['pass'];
      }
    }

    try
    {
      $client = new Zend_Http_Client(saPluginManager::getPluginListBaseUrl().SFADVANCED_VERSION.'.yml', $config);
      $response = $client->request();

      if ($response->isSuccessful())
      {
        $list = sfYaml::load($response->getBody());
        $list = $this->applyLocalPluginList($list);
      }
      else
      {
        $str = "Failed to download plugin list.";
        $this->logBlock($str, 'ERROR');
      }
    }
    catch (Zend_Http_Client_Adapter_Exception $e)
    {
      $str = "No Internet Connection.";
      $this->logBlock($str, 'COMMENT');
    }

    return $list;
  }

  protected function applyLocalPluginList($pluginList)
  {
    $path = sfConfig::get('sf_config_dir').'/plugins.yml';
    if (!is_readable($path))
    {
      return $pluginList;
    }

    $mergedList = array();
    $localList = (array)sfYaml::load($path);

    $default = array();
    if (isset($localList['all']))
    {
      $default = $localList['all'];
      unset($localList['all']);
    }

    foreach ($pluginList as $key => $value)
    {
      if (array_key_exists($key, $localList))
      {
        $mergedList[$key] = sfToolkit::arrayDeepMerge($value, (array)$localList[$key]);
      }
      else
      {
        $mergedList[$key] = sfToolkit::arrayDeepMerge($value, (array)$default);
      }
    }

    foreach ($localList as $key => $value)
    {
      if (!isset($mergedList[$key]))
      {
        $mergedList[$key] = $value;
      }
    }

    return $mergedList;
  }
}
