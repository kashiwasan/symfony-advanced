<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opCommunityComponents
 *
 * @package    SfAdvanced
 * @subpackage action
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
abstract class opCommunityComponents extends sfComponents
{
  public function executeCautionAboutCommunityMemberPre()
  {
    $memberId = sfContext::getInstance()->getUser()->getMemberId();

    $this->communityMembersCount = Doctrine::getTable('CommunityMember')->countCommunityMembersPre($memberId);
  }

  public function executeCautionAboutChangeAdminRequest()
  {
    $this->communityCount = Doctrine::getTable('Community')->countPositionRequestCommunities('admin');
  }

  public function executeCautionAboutSubAdminRequest()
  {
    $this->communityCount = Doctrine::getTable('Community')->countPositionRequestCommunities('sub_admin');
  }

}
