<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

error_reporting(error_reporting() & ~(E_STRICT | E_DEPRECATED));

/**
 * saInstalledPluginManager allows you to manage installed SfAdvanced plugins.
 *
 * @package    SfAdvanced
 * @subpackage plugin
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saInstalledPluginManager
{
  public function getInstalledApplicationPlugins()
  {
    $result = $this->getInstalledPlugins();

    foreach ($result as $k => $v)
    {
      if (!$v->isApplicationPlugin())
      {
        unset($result[$k]);
      }
    }

    return $result;
  }

  public function getInstalledAuthPlugins()
  {
    $result = $this->getInstalledPlugins();

    foreach ($result as $k => $v)
    {
      if (!$v->isAuthPlugin())
      {
        unset($result[$k]);
      }
    }

    return $result;
  }

  public function getInstalledSkinPlugins()
  {
    $result = $this->getInstalledPlugins();

    foreach ($result as $k => $v)
    {
      if (!$v->isSkinPlugin())
      {
        unset($result[$k]);
      }
    }

    return $result;
  }

  public function getInstalledPlugins()
  {
    $result = array();

    $plugins = sfContext::getInstance()->getConfiguration()->getAllSfAdvancedPlugins();
    foreach ($plugins as $pluginName)
    {
      $result[$pluginName] = $this->getPluginInstance($pluginName);
    }

    return $result;
  }

  public function getPluginInstance($plugin)
  {
    return saPlugin::getInstance($plugin);
  }

  static public function getAdminInviteAuthPlugins()
  {
    $plugins = sfContext::getInstance()->getConfiguration()->getEnabledAuthPlugin();
    $plugins = array_unique($plugins);
    $result = array();

    foreach ($plugins as $pluginName)
    {
      $endPoint = strlen($pluginName) - strlen('saAuth') - strlen('Plugin');
      $authMode = substr($pluginName, strlen('saAuth'), $endPoint);

      $adapterClass = saSecurityUser::getAuthAdapterClassName($authMode);
      $adapter = new $adapterClass($authMode);
      if (!$adapter->getAuthConfig('admin_invite'))
      {
        continue;
      }

      $result[] = $authMode;
    }

    return $result;
  }
}
