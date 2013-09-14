<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class saPluginActivateTask extends sfBaseTask
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
    $this->name             = 'activate';
    $this->briefDescription = 'Activates the installed plugin.';
    $this->detailedDescription = <<<EOF
The [saPlugin:activate|INFO] task activates the installed plugin.
Call it with:

  [./symfony saPlugin:activate saSamplePlugin|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $configuration = $this->createConfiguration('pc_frontend', 'cli');
    $name = $arguments['name'];

    if (!$configuration->isPluginExists($name))
    {
      throw new sfException(sprintf('Plugin "%s" does not installed', $name));
    }

    if ($configuration->isEnabledPlugin($name))
    {
      throw new sfException(sprintf('Plugin "%s" is already activated', $name));
    }

    saPlugin::getInstance($name)->setIsActive(true);

    $cc = new sfCacheClearTask($this->dispatcher, $this->formatter);
    $cc->run();
  }
}
