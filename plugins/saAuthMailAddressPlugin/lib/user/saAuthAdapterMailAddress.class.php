<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saAuthAdapterMailAddress will handle credential for E-mail address.
 *
 * @package    SfAdvanced
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saAuthAdapterMailAddress extends saAuthAdapter
{
  protected $authModuleName = 'saAuthMailAddress';

 /**
  * @see saAuthAdapter::activate()
  */
  public function activate()
  {
    parent::activate();

    $member = sfContext::getInstance()->getUser()->getMember();
    if ($member)
    {
      if ($token = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('mobile_address_token', $member->getId()))
      {
        $token->delete();
      }

      if ($token = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('pc_address_token', $member->getId()))
      {
        $token->delete();
      }
    }

    return $member;
  }

  /**
   * Returns true if the current state is a beginning of register.
   *
   * @return bool returns true if the current state is a beginning of register, false otherwise
   */
  public function isRegisterBegin($member_id = null)
  {
    saActivateBehavior::disable();
    $member = Doctrine::getTable('Member')->find((int)$member_id);
    saActivateBehavior::enable();

    if (!$member)
    {
      return false;
    }

    if (!Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('pc_address_pre', $member->getId())
      && !Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('mobile_address_pre', $member->getId()))
    {
      return false;
    }

    if (!$member->getIsActive())
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  /**
   * Returns true if the current state is a end of register.
   *
   * @return bool returns true if the current state is a end of register, false otherwise
   */
  public function isRegisterFinish($member_id = null)
  {
    saActivateBehavior::disable();
    $data = Doctrine::getTable('Member')->find((int)$member_id);
    saActivateBehavior::enable();

    if (!$data || !$data->getName() || !$data->getProfiles())
    {
      return false;
    }

    if ($data->getIsActive())
    {
      return false;
    }
    else
    {
      return true;
    }
  }
}
