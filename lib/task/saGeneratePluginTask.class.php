<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class saGeneratePluginTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('plugin', sfCommandArgument::REQUIRED, 'The SfAdvanced plugin name'),
    ));
      
    $this->namespace        = 'saGenerate';
    $this->name             = 'plugin';
    $this->briefDescription = 'Generates a new SfAdvanced plugin';
    $this->detailedDescription = <<<EOF
The [saGenerate:plugin|INFO] task creates the basic directory structure
for a new plugin in the SfAdvanced project:

  [./symfony saGenerate:plugin saSamplePlugin|INFO]

If a plugin with the same name already exists,
it throws a [sfCommandException|COMMENT].
EOF;
  }

  protected function execute($arguments = array(), $sations = array())
  {
    $plugin = $arguments['plugin'];

    // Validate the application name
    if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $plugin))
    {
      throw new sfCommandException(sprintf('The SfAdvanced plugin name "%s" is invalid.', $plugin));
    }

    $saPluginDir = sfConfig::get('sf_plugins_dir').'/'.$plugin;

    if (is_dir($saPluginDir))
    {
      throw new sfCommandException(sprintf('The SfAdvanced plugin "%s" already exists.', $saPluginDir));
    }
      
    // create basic saPlugin structure
    $finder = sfFinder::type('any')->discard('.sf');
    $this->getFilesystem()->mirror(dirname(__FILE__).'/skeleton/saPlugin', $saPluginDir, $finder);

    $fixPerms = new sfadvancedPermissionTask($this->dispatcher, $this->formatter);
    $fixPerms->setCommandApplication($this->commandApplication);
    @$fixPerms->run();
  }
}
