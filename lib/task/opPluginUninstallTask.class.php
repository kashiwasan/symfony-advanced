<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class saPluginUninstallTask extends sfPluginUninstallTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'The plugin name'),
    ));

    $this->addOptions(array(
      new sfCommandOption('channel', 'c', sfCommandOption::PARAMETER_REQUIRED, 'The PEAR channel name', null),
      new sfCommandOption('install_deps', 'd', sfCommandOption::PARAMETER_NONE, 'Whether to force installation of dependencies', null),
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', null),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
    ));

    $this->namespace        = 'saPlugin';
    $this->name             = 'uninstall';
    $this->briefDescription = 'Uninstalls the SfAdvanced plugin';
    $this->detailedDescription = <<<EOF
The [saPlugin:uninstall|INFO] task uninstalls the SfAdvanced plugin.
Call it with:

  [./symfony saPlugin:uninstall saSamplePlugin|INFO]
EOF;
  }

  public function getPluginManager()
  {
    // Remove E_STRICT and E_DEPRECATED from error_reporting
    error_reporting(error_reporting() & ~(E_STRICT | E_DEPRECATED));

    $oldPluginManager = parent::getPluginManager();
    $pluginManager = new saPluginManager($this->dispatcher, $oldPluginManager->getEnvironment());

    return $pluginManager;
  }

  protected function execute($arguments = array(), $sations = array())
  {
    if (empty($sations['channel']))
    {
      $sations['channel'] = saPluginManager::getDefaultPluginChannelServerName();
    }

    return parent::execute($arguments, $sations);
  }
}
