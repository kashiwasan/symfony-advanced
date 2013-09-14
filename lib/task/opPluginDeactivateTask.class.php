<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class saPluginDeactivateTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'The plugin name'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', null),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
    ));

    $this->namespace        = 'saPlugin';
    $this->name             = 'deactivate';
    $this->briefDescription = 'Deactivates the installed plugin.';
    $this->detailedDescription = <<<EOF
The [saPlugin:deactivate|INFO] task deactivates the installed plugin.
Call it with:

  [./symfony saPlugin:deactivate saSamplePlugin|INFO]
EOF;
  }

  protected function execute($arguments = array(), $sations = array())
  {
    $configuration = $this->createConfiguration('pc_frontend', 'cli');
    $name = $arguments['name'];

    if (!$configuration->isPluginExists($name))
    {
      throw new sfException(sprintf('Plugin "%s" does not installed', $name));
    }

    if ($configuration->isDisabledPlugin($name))
    {
      throw new sfException(sprintf('Plugin "%s" is already disactivated', $name));
    }

    saPlugin::getInstance($name)->setIsActive(false);

    $cc = new sfCacheClearTask($this->dispatcher, $this->formatter);
    $cc->run();
  }
}
