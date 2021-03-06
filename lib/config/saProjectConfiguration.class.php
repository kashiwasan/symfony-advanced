<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

if (!defined('E_DEPRECATED'))
{
  define('E_DEPRECATED', 8192);
}

/**
 * saProjectConfiguration
 *
 * @package    SfAdvanced
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saProjectConfiguration extends sfProjectConfiguration
{
  static public function listenToPreCommandEvent(sfEvent $event)
  {
    $subject = $event->getSubject();
    if ($subject instanceof sfTask)
    {
      sfConfig::set('sf_task_name', get_class($subject));
    }
    require_once dirname(__FILE__).'/../behavior/saActivateBehavior.class.php';
    saActivateBehavior::disable();
  }

  public function setup()
  {
    $this->configurePluginPath();
    $this->enableAllPluginsExcept();
    $this->setIncludePath();

    require_once dirname(__FILE__).'/../util/saToolkit.class.php';

    $this->setSfAdvancedConfiguration();

    sfConfig::set('doctrine_model_builder_options', array(
      'baseClassName' => 'saDoctrineRecord',
    ));

    $this->dispatcher->connect('command.pre_command', array(__CLASS__, 'listenToPreCommandEvent'));
    $this->dispatcher->connect('doctrine.filter_cli_config', array(__CLASS__, 'filterDoctrineCliConfig'));

    $this->setupProjectSfAdvanced();
  }

  public function configurePluginPath()
  {
    // search for *Plugin directories representing plugins
    // follow links and do not recurse. No need to exclude VC because they do not end with *Plugin
    $finder = sfFinder::type('dir')->maxdepth(0)->ignore_version_control(false)->follow_link()->name('*Plugin');
    $dirs = array(
      sfConfig::get('sf_lib_dir').'/plugins',
    );

    foreach ($finder->in($dirs) as $path)
    {
      $this->setPluginPath(basename($path), $path);
    }
  }

  protected function configureSessionStorage($name, $options = array())
  {
    $sessionName = 'SfAdvanced_'.sfConfig::get('sf_app', 'default');
    $params = array('session_name' => $sessionName);

    if ('memcache' === $name)
    {
      sfConfig::set('sf_factory_storage', 'saMemcacheSessionStorage');
      sfConfig::set('sf_factory_storage_parameters', array_merge((array)$options, $params));
    }
    elseif ('database' === $name)
    {
      sfConfig::set('sf_factory_storage', 'saPDODatabaseSessionStorage');
      sfConfig::set('sf_factory_storage_parameters', array_merge(array(
        'db_table'    => 'session',
        'database'    => 'doctrine',
        'db_id_col'   => 'id',
        'db_data_col' => 'session_data',
        'db_time_col' => 'time',
      ), (array)$options, $params));
    }
    elseif ('file' !== $name)
    {
      sfConfig::set('sf_factory_storage', $name);
      sfConfig::set('sf_factory_storage_parameters', array_merge((array)$options, $params));
    }
  }

  public function setIncludePath()
  {
    sfToolkit::addIncludePath(array(
      dirname(__FILE__).'/../vendor/PEAR/',
      dirname(__FILE__).'/../vendor/OAuth/',
      dirname(__FILE__).'/../vendor/simplepie/',
    ));
  }

  public function configureDoctrine($manager)
  {
    spl_autoload_register(array('Doctrine', 'extensionsAutoload'));
    Doctrine::setExtensionsPath(sfConfig::get('sf_lib_dir').'/vendor/doctrine_extensions');
    $manager->registerExtension('ExtraFunctions');

    $manager->setAttribute(Doctrine::ATTR_AUTOLOAD_TABLE_CLASSES, true);
    $manager->setAttribute(Doctrine::ATTR_RECURSIVE_MERGE_FIXTURES, true);
    $manager->setAttribute(Doctrine::ATTR_QUERY_CLASS, 'saDoctrineQuery');

    if (extension_loaded('apc'))
    {
      $options = array();
      if ($prefix = sfConfig::get('sa_doctrine_cache_key_prefix'))
      {
        $options['prefix'] = $prefix;
      }
      else
      {
        $options['prefix'] = md5(dirname(__FILE__));
      }
      $cacheDriver = new Doctrine_Cache_Apc($options);
      $manager->setAttribute(Doctrine::ATTR_QUERY_CACHE, $cacheDriver);
    }

    $manager->registerConnectionDriver('mysql', 'saDoctrineConnectionMysql');
    $manager->registerConnectionDriver('pgsql', 'Doctrine_Connection_Pgsql_ExtraFunctions');
    $manager->registerConnectionDriver('sqlite', 'Doctrine_Connection_Sqlite_ExtraFunctions');

    $this->setupProjectSfAdvancedDoctrine($manager);
  }

  protected function setSfAdvancedConfiguration()
  {
    $saConfigCachePath = sfConfig::get('sf_cache_dir').DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'SfAdvanced.yml.php';
    if (is_readable($saConfigCachePath))
    {
      $config = (array)include($saConfigCachePath);
    }
    else
    {
      $path = SFADVANCED_CONFIG_DIR.'/SfAdvanced.yml';
      $config = sfYaml::load($path.'.sample');
      if (is_readable($path))
      {
        $config = sfToolkit::arrayDeepMerge($config, sfYaml::load($path));
      }

      if (isset($config['base_url']))
      {
        $config['base_url'] = preg_replace('/\/$/', '', $config['base_url']);
      }

      saToolkit::writeCacheFile($saConfigCachePath, '<?php return '.var_export($config, true).';');
    }
    $this->configureSessionStorage($config['session_storage']['name'], (array)$config['session_storage']['options']);
    unset($config['session_storage']);

    foreach ($config as $key => $value)
    {
      sfConfig::set('sa_'.$key, $value);
    }
  }

  static public function filterDoctrineCliConfig($event, $config)
  {
    $config['migrations_path'] = sfConfig::get('sf_data_dir').'/migrations/generated';

    return $config;
  }
}
