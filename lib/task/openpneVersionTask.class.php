<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * sfadvancedVersionTask
 *
 * @package    SfAdvanced
 * @subpackage task
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfadvancedVersionTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', true),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));

    $this->namespace        = 'sfadvanced';
    $this->name             = 'version';
    $this->briefDescription = 'Show version information of SfAdvanced and all installed plugins';
    $this->detailedDescription = <<<EOF
The [sfadvanced:version|INFO] task shows version information of SfAdvanced and all installed plugins.
Call it with:

  [./symfony sfadvanced:version|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->log($this->formatter->format('Core versions:', 'COMMENT'));

    $this->displayLine('SfAdvanced', SFADVANCED_VERSION);
    $this->displayLine('symfony', SYMFONY_VERSION);

    $this->log($this->formatter->format('SfAdvanced plugin versions:', 'COMMENT'));

    foreach ($this->configuration->getAllSfAdvancedPlugins() as $name)
    {
      $version = opPlugin::getInstance($name, $this->dispatcher)->getVersion();
      if (!$version)
      {
        $version = 'unknown';
      }
      $this->displayLine($name, $version);
    }
  }

  protected function displayLine($name, $version)
  {
    $this->log(sprintf(' %-40s %s', $this->formatter->format($name, 'INFO'), $version));
  }
}
