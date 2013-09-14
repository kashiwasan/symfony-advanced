<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * community actions.
 *
 * @package    SfAdvanced
 * @subpackage community
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class communityActions extends saCommunityAction
{
 /**
  * Executes home action
  *
  * @param saWebRequest $request A request object
  */
  public function executeHome(saWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'community', 'smtHome');

    return parent::executeHome($request);
  }

 /**
  * Executes smtHome action
  *
  * @param saWebRequest $request A request object
  */
  public function executeSmtHome(saWebRequest $request)
  {
    $gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('smartphoneCommunity');
    $this->contentsGadgets = $gadgets['smartphoneCommunityContents'];

    $this->community = Doctrine::getTable('Community')->find($this->id);
    $this->forward404Unless($this->community);

    saSmartphoneLayoutUtil::setLayoutParameters(array('community' => $this->community));

    return sfView::SUCCESS;
  }

 /**
  * Executes edit action
  *
  * @param saWebRequest $request A request object
  */
  public function executeEdit(saWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'community', 'smtEdit');

    $this->enableImage = true;
    $result = parent::executeEdit($request);

    if ($this->community->isNew()) {
      sfConfig::set('sf_nav_type', 'default');
    }


    return $result;
  }

 /**
  * Executes smtEdit action
  *
  * @param saWebRequest $request A request object
  */
  public function executeSmtEdit(saWebRequest $request)
  {
    $result = parent::executeEdit($request);

    if ($this->community->isNew())
    {
      $this->setLayout('smtLayoutHome');
    }
    else
    {
      saSmartphoneLayoutUtil::setLayoutParameters(array('community' => $this->community));
    }

    return $result;
  }

 /**
  * Executes memberList action
  *
  * @param saWebRequest $request A request object
  */
  public function executeMemberList($request)
  {
    $this->forwardIf($request->isSmartphone(), 'community', 'smtMemberList');

    return parent::executeMemberList($request);
  }

 /**
  * Executes smtMemberList action
  *
  * @param saWebRequest $request A request object
  */
  public function executeSmtMemberList(saWebRequest $request)
  {
    $result = parent::executeMemberList($request);

    saSmartphoneLayoutUtil::setLayoutParameters(array('community' => $this->community));

    return $result;
  }

 /**
  * Executes joinlist action
  *
  * @param saWebRequest $request A request object
  */
  public function executeJoinlist(saWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'community', 'smtJoinlist');

    sfConfig::set('sf_nav_type', 'default');

    if ($request->hasParameter('id') && $request->getParameter('id') != $this->getUser()->getMemberId())
    {
      sfConfig::set('sf_nav_type', 'friend');
    }

    return parent::executeJoinlist($request);
  }

 /**
  * Executes smtJoinlist action
  *
  * @param saWebRequest $request A request object
  */
  public function executeSmtJoinlist(saWebRequest $request)
  {
    $result = parent::executeJoinlist($request);

    if ($request['id'] && $request['id'] !== $this->getUser()->getMemberId())
    {
      $this->targetMember = Doctrine::getTable('Member')->find((int)$request['id']);
    }
    else
    {
      $this->targetMember = $this->getUser()->getMember();
    }

    saSmartphoneLayoutUtil::setLayoutParameters(array('member' => $this->member)); 

    return $result;
  }

 /**
  * Executes join action
  *
  * @param saWebRequest $request A request object
  */
  public function executeJoin(saWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'community', 'smtJoin');

    return parent::executeJoin($request);
  }

 /**
  * Executes smtJoin action
  *
  * @param saWebRequest $request A request object
  */
  public function executeSmtJoin(saWebRequest $request)
  {
    $result = parent::executeJoin($request);

    saSmartphoneLayoutUtil::setLayoutParameters(array('community' => $this->community));

    return $result;
  }

 /**
  * Executes quit action
  *
  * @param saWebRequest $request A request object
  */
  public function executeQuit(saWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'community', 'smtQuit');

    return parent::executeQuit($request);
  }

 /**
  * Executes smtJoin action
  *
  * @param saWebRequest $request A request object
  */
  public function executeSmtQuit(saWebRequest $request)
  {
    $result = parent::executeQuit($request);

    saSmartphoneLayoutUtil::setLayoutParameters(array('community' => $this->community));

    return $result;
  }

 /**
  * Executes search action
  *
  * @param saWebRequest $request A request object
  */
  public function executeSearch(saWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'community', 'smtSearch');

    return parent::executeSearch($request);
  }

 /**
  * Executes smtSearch action
  *
  * @param saWebRequest $request A request object
  */
  public function executeSmtSearch(saWebRequest $request)
  {
    return sfView::SUCCESS;
  }
}
