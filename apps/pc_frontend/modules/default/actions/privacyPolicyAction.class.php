<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * privacy policy actions.
 *
 * @package    SfAdvanced
 * @subpackage default
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class privacyPolicyAction extends sfAction
{
 /**
  * Executes this action
  *
  * @param sfRequest $request A request object
  */
  public function execute($request)
  {
    if ($request->isSmartphone())
    {
      $this->setLayout('smtLayoutSns');
      $this->setTemplate('smtPrivacyPolicy');
    }
  }
}
