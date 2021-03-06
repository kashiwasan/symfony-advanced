<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saAuthAction
 *
 * @package    SfAdvanced
 * @subpackage action
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saAuthAction extends sfActions
{
  public function executeRegisterEnd(sfWebRequest $request)
  {
    $this->forward404Unless($this->getUser()->setRegisterToken($request['token']));

    $member = $this->getUser()->getMember(true);

    if (saConfig::get('retrieve_uid') == 3
      && !sfConfig::get('app_is_mobile', false)
      && !$member->getConfig('mobile_uid')
    )
    {
      $this->forward('member', 'registerMobileToRegisterEnd');
    }

    $this->getUser()->getAuthAdapter()->activate();

    $this->getUser()->setIsSNSMember(true);

    if ($member->getEmailAddress())
    {
      $i18n = sfContext::getInstance()->getI18N();
      $params = array(
        'subject' => $i18n->__('Notify of Your Registering'),
        'url'     => $this->getController()->genUrl(array('sf_route' => 'homepage'), true),
      );
      saMailSend::sendTemplateMailToMember('registerEnd', $member, $params);
    }

    $this->redirect('@homepage');
  }
}
