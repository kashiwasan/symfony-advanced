<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision29_FixOpDiaryPluginRevision extends Doctrine_Migration_Base
{
  public function up()
  {
    // and try to fix community topic revision if the plugin is exists
    $conn = Doctrine_Manager::getInstance()->getConnectionForComponent('SiteConfig');
    $result = $conn->fetchOne('SELECT value FROM sns_config WHERE name = ?', array('saDiaryPlugin_revision'));
    if (!$result)
    {
      Doctrine::getTable('SiteConfig')->set('saDiaryPlugin_revision', '3');
    }
  }
}
