<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class addNavigationItemForMobileHomeCenter extends Doctrine_Migration_Base
{
  public function up()
  {
    $navi = new Navigation();
    $navi->Translation['en']->caption = 'Profile';
    $navi->Translation['ja_JP']->caption = '[i:140]ﾌﾟﾛﾌｨｰﾙ';
    $navi->setType('mobile_home_center');
    $navi->setUri('member/profile');
    $navi->setSortOrder(0);
    $navi->save();

    // and try to fix community topic revision if the plugin is exists
    $conn = Doctrine_Manager::getInstance()->getConnectionForComponent('SiteConfig');
    $result = $conn->fetchOne('SELECT value FROM sns_config WHERE name = ?', array('saCommunityTopicPlugin_revision'));
    if (!$result)
    {
      Doctrine::getTable('SiteConfig')->set('saCommunityTopicPlugin_revision', '4');
    }
  }

  public function down()
  {
  }
}
