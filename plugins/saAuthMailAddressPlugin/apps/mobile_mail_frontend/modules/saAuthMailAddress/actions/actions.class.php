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
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class saAuthMailAddressActions extends sfActions
{
 /**
  * Executes register
  *
  * @param sfWebRequest A request object
  */
  public function executeRegister(sfWebRequest $request)
  {
    if ($this->getRoute()->getMember())
    {
      $this->forward('saAuthMailAddress', 'login');
    }

    $adapter = new saAuthAdapterMailAddress('MailAddress');
    if ($adapter->getAuthConfig('invite_mode') < 2)
    {
      return sfView::NONE;
    }

    $message = $request->getMailMessage();

    $this->form = new saRequestRegisterURLForm(null, array('authMode' => 'MailAddress'));
    $this->form->bind(array('mail_address' => $message->from));
    if ($this->form->isValid())
    {
      $this->form->sendMail();
    }

    return sfView::NONE;
  }

 /**
  * Executes login
  *
  * @param sfWebRequest A request object
  */
  public function executeLogin(sfWebRequest $request)
  {
    if (!$this->getRoute()->getMember())
    {
      $this->forward('saAuthMailAddress', 'register');
    }
  }
}
