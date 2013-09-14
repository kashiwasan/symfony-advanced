<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saChangeCommunityAdminRequestForm
 *
 * @package    SfAdvanced
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saCommunityJoiningForm extends saBaseForm
{
  public function setup()
  {
    $this->widgetSchema->setNameFormat('community_join[%s]');
  }
}
