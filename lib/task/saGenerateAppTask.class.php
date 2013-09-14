<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class saGenerateAppTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('plugin', sfCommandArgument::REQUIRED, 'The SfAdvanced plugin name'),
      new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name'),
    ));

    $this->namespace = 'saGenerate';
    $this->name = 'app';
    $this->briefDescription = 'Generates a new application for SfAdvanced plugin';
    $this->detailedDescription = <<<EOF
The [saGenerate:app|INFO] task creates the basic directory structure
for a new application in an existing SfAdvanced plugin:

  [./symfony saGenerate:app saSamplePlugin pc_frontend|INFO]

If an application with the same name already exists in the plugin,
it throws a [sfCommandException|COMMENT].
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $plugin = $arguments['plugin'];
    $app = $arguments['application'];

    // Validate the application name
    if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $app))
    {
      throw new sfCommandException(sprintf('The application name "%s" is invalid.', $app));
    }

    $appDir = sfConfig::get('sf_plugins_dir').'/'.$plugin.'/apps/'.$app;

    if (is_dir($appDir))
    {
      throw new sfCommandException(sprintf('The application "%s" already exists in the "%s" plugin.', $appDir, $plugin));
    }

    // Create basic application structure
    $finder = sfFinder::type('any')->discard('.sf');
    $this->getFilesystem()->mirror(dirname(__FILE__).'/skeleton/app/app', $appDir, $finder);

    $fixPerms = new sfadvancedPermissionTask($this->dispatcher, $this->formatter);
    $fixPerms->setCommandApplication($this->commandApplication);
    $fixPerms->run();
  }
}
