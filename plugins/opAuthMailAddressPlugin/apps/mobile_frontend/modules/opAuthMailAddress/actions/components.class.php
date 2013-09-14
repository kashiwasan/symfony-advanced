<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opAuthMailAddressComponents extends sfComponents
{
  public function executeRegisterBox($request)
  {
    $token = $request->getParameter('token');
    $memberConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndValue('register_token', $token);
    opActivateBehavior::disable();
    $this->mobileAddressPre = $memberConfig->getMember()->getConfig('mobile_address_pre');
    opActivateBehavior::enable();
  }
}
