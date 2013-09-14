<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * CommunityConfig form.
 *
 * @package    SfAdvanced
 * @subpackage form
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class FriendLinkForm extends opBaseForm
{
  public function configure()
  {
    $this->widgetSchema->setNameFormat('friend_link[%s]');
  }
}
