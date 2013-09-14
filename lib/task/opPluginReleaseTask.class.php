<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class saPluginReleaseTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'The plugin name'),
      new sfCommandArgument('dir', sfCommandArgument::REQUIRED, 'The output dir'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', null),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('channel', 'c', sfCommandOption::PARAMETER_REQUIRED, 'The PEAR channel name', null),
    ));

    $this->namespace        = 'saPlugin';
    $this->name             = 'release';
    $this->briefDescription = 'Creates the plugin definition file and archive the SfAdvanced plugin.';
    $this->detailedDescription = <<<EOF
The [saPlugin:release|INFO] task creates the plugin definition file, and archive the SfAdvanced plugin.
Call it with:

  [./symfony saPlugin:release saSamplePlugin|INFO]
EOF;
  }

  protected function execute($arguments = array(), $sations = array())
  {
    $name = $arguments['name'];
    $dir = $arguments['dir'];

    if (empty($sations['channel']))
    {
      $sations['channel'] = saPluginManager::getDefaultPluginChannelServerName();
    }

    while (
      !($version = $this->ask('Type plugin version'))
      || !preg_match('/^[.\d]+(alpha|beta|rc)?[.\d]*$/i', $version)
    )
    {
      $this->logBlock('invalid format.', 'ERROR');
    }

    while (
      !($stability = $this->ask('Choose stability (stable, alpha, beta or devel)'))
      || !in_array($stability, array('stable', 'alpha', 'beta', 'devel'))
    )
    {
      $this->logBlock('invalid format.', 'ERROR');
    }

    while (!($note = $this->ask('Type release note')));

    $this->logBlock('These informations are inputed:', 'COMMENT');
    $this->log($this->formatList(array(
      'The Plugin Name     ' => $name,
      'The Plugin Version  ' => $version,
      'The Plugin Stability' => $stability,
      'The Release note    ' => $note,
    )));

    if ($this->askConfirmation('Is it OK to start this task? (y/n)'))
    {
      $this->doRelease($name, $version, $stability, $note, $dir, $sations['channel']);
      $this->clearCache();
    }
  }

  protected function doRelease($name, $version, $stability, $note, $dir, $channel)
  {
    $defineTask = new saPluginDefineTask($this->dispatcher, $this->formatter);
    $defineTask->run(array('name' => $name, 'version' => $version, 'stability' => $stability, 'note' => '"'.$note.'"'), array('channel' => $channel));
    $archiveTask = new saPluginArchiveTask($this->dispatcher, $this->formatter);
    $archiveTask->run(array('name' => $name, 'dir' => $dir));
  }

  protected function clearCache()
  {
    $task = new sfCacheClearTask($this->dispatcher, $this->formatter);
    $task->run();
  }

  protected function formatList($list)
  {
    $result = '';

    foreach ($list as $key => $value)
    {
      $result .= $this->formatter->format($key, 'INFO')."\t";
      $result .= $value."\n";
    }

    return $result;
  }
}
