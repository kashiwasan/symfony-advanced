<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class saAuthMailAddressComponents extends sfComponents
{
  public function executeRegisterBox($request)
  {
    $token = $request->getParameter('token');
    $memberConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndValue('register_token', $token);
    saActivateBehavior::disable();
    $member = $memberConfig->getMember();
    $this->addressPre = $member->getConfig('pc_address_pre') || $member->getConfig('mobile_address_pre');
    saActivateBehavior::enable();
  }
}
