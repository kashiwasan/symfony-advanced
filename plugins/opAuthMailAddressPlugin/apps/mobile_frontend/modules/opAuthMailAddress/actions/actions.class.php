<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saAuthMailAddress actions.
 *
 * @package    SfAdvanced
 * @subpackage saAuthMailAddressPlugin
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saAuthMailAddressActions extends saAuthMailAddressPluginAction
{
  public function executeRequestRegisterURL($request)
  {
    $adapter = new saAuthAdapterMailAddress('MailAddress');
    if ($adapter->getAuthConfig('invite_mode') < 2)
    {
      $this->forward404();
    }

    $this->forward404Unless(saToolkit::isEnabledRegistration());

    return sfView::INPUT;
  }

  public function executeRegister($request)
  {
    $this->getUser()->setCurrentAuthMode('MailAddress');

    $token = $request->getParameter('token');
    $memberConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndValue('register_token', $token);
    $this->forward404Unless($memberConfig, 'This URL is invalid.');

    saActivateBehavior::disable();
    $authMode = $memberConfig->getMember()->getConfig('register_auth_mode');
    $mobileAddressPre = $memberConfig->getMember()->getConfig('mobile_address_pre');
    saActivateBehavior::enable();

    if ('MobileUID' === $authMode)
    {
      $authMode = 'MailAddress';
    }
    $this->forward404Unless($authMode === $this->getUser()->getCurrentAuthMode());

    if (!$mobileAddressPre)
    {
      
      return sfView::ERROR;
    }

    $this->getUser()->setMemberId($memberConfig->getMemberId());
    $this->getUser()->setIsSNSRegisterBegin(true);

    $this->redirect('member/registerInput?token='.$token);
  }
}
