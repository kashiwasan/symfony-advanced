<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

// Remove E_STRICT and E_DEPRECATED from error_reporting
error_reporting(error_reporting() & ~(E_STRICT | E_DEPRECATED));

/**
 * saPlugin allows you to touch SfAdvanced plugin.
 *
 * @package    SfAdvanced
 * @subpackage plugin
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saPlugin
{
  private static $instances = array();

  protected
    $name,
    $isActive = true,
    $version,
    $summary;

  private function __construct($pluginName, sfEventDispatcher $dispatcher)
  {
    $this->name = $pluginName;

    $config = saPluginManager::getPluginActivationList();
    if (isset($config[$pluginName]))
    {
      $this->isActive = $config[$pluginName];
    }

    $info = $this->getPackageInfo();
    if ($info)
    {
      $this->version = (string)$info->version->release;
      $this->summary = (string)$info->summary;
    }
    else
    {
      $manager = new saPluginManager($dispatcher);
      $package = $manager->getEnvironment()->getRegistry()->getPackage($pluginName, saPluginManager::getDefaultPluginChannelServerName());
      if ($package)
      {
        $this->version = $package->getVersion();
        $this->summary = $package->getSummary();
      }
    }
  }

  public static function getInstance($pluginName, sfEventDispatcher $dispatcher = null)
  {
    if (is_null($dispatcher))
    {
      $dispatcher = sfContext::getInstance()->getEventDispatcher();
    }

    if (empty(self::$instances[$pluginName]))
    {
      self::$instances[$pluginName] = new saPlugin($pluginName, $dispatcher);
    }

    return self::$instances[$pluginName];
  }

  public function getName()
  {
    return $this->name;
  }

  public function getIsActive()
  {
    if (sfContext::hasInstance())
    {
      return sfContext::getInstance()->getConfiguration()->isEnabledPlugin($this->name);
    }
    else
    {
      return $this->isActive;
    }
  }

  public function getVersion()
  {
    return $this->version;
  }

  public function getSummary()
  {
    return $this->summary;
  }

  public function hasBackend()
  {
    $path = '/apps/pc_backend/modules/'.$this->getName().'/actions';
    return (bool)sfContext::getInstance()->getConfiguration()->globEnablePlugin($path);
  }

  protected function getPackageInfo()
  {
    $xmlPath = sfConfig::get('sf_plugins_dir').'/'.$this->getName().'/package.xml';
    if (!is_readable($xmlPath))
    {
      return false;
    }
    $content = file_get_contents($xmlPath);

    return saToolkit::loadXmlString($content, array(
      'return' => 'SimpleXMLElement',
    ));
  }

  public function setIsActive($isActive)
  {
    $plugin = Doctrine::getTable('Plugin')->findOneByName($this->name);
    if (!$plugin)
    {
      $plugin = Doctrine::getTable('Plugin')->create(array('name' => $this->name));
    }
    $plugin->is_enabled = $isActive;
    $plugin->save();
  }

  public function isAuthPlugin()
  {
    return (0 === strpos($this->name, 'saAuth'));
  }

  public function isSkinPlugin()
  {
    return (0 === strpos($this->name, 'saSkin'));
  }

  public function isApplicationPlugin()
  {
    return (!$this->isAuthPlugin() && !$this->isSkinPlugin());
  }
}
