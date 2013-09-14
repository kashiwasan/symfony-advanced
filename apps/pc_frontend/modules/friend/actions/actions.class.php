<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * friend actions.
 *
 * @package    SfAdvanced
 * @subpackage friend
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class friendActions extends saFriendAction
{
  public function preExecute()
  {
    parent::preExecute();

    if ($this->id == $this->getUser()->getMemberId())
    {
      sfConfig::set('sf_nav_type', 'default');
    }
  }

  public function executeList(saWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'friend', 'smtList');

    $this->size = 50;

    return parent::executeList($request);
  }

  public function executeSmtList(saWebRequest $request)
  {
    $this->member = Doctrine::getTable('Member')->find($this->id);
    saSmartphoneLayoutUtil::setLayoutParameters(array('member' => $this->member));

    return sfView::SUCCESS;
  }

  public function executeLink(saWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'friend', 'smtLink');

    return parent::executeLink($request);
  }

  public function executeSmtLink(saWebRequest $request)
  {
    $result = parent::executeLink($request);

    saSmartphoneLayoutUtil::setLayoutParameters(array('member' => $this->member));

    return $result;
  }
}
