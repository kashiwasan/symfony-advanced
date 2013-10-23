<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * member actions.
 *
 * @package    SfAdvanced
 * @subpackage member
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class memberActions extends saMemberAction
{
 /**
  * Executes home action
  *
  * @param saWebRequest $request A request object
  */
  public function executeHome(saWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'member', 'smtHome');

    $this->topGadgets = null;
    $this->sideMenuGadgets = null;

    $this->gadgetConfig = sfConfig::get('sa_gadget_list');

    $gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('gadget');
    $layout = Doctrine::getTable('SiteConfig')->get('home_layout', 'layoutA');
    $this->setLayout($layout);

    switch ($layout)
    {
      case 'layoutA' :
        $this->topGadgets = $gadgets['top'];
      case 'layoutB' :
        $this->sideMenuGadgets = $gadgets['sideMenu'];
    }

    $this->contentsGadgets = $gadgets['contents'];
    $this->bottomGadgets = $gadgets['bottom'];

    return parent::executeHome($request);
  }

 /**
  * Execute smtHome action
  *
  * @param saWebRequest $request A request object
  */
  public function executeSmtHome(saWebRequest $request)
  {
    $this->gadgetConfig = sfConfig::get('sa_smartphone_gadget_list');

    $gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('smartphone');
    $this->contentsGadgets = $gadgets['smartphoneContents'];

    return sfView::SUCCESS;
  }

 /**
  * Executes login action
  *
  * @param saWevRequest $request A request object
  */
  public function executeLogin(saWebRequest $request)
  {
    if (saConfig::get('external_pc_login_url') && $request->isMethod(sfWebRequest::GET))
    {
      $this->redirect(saConfig::get('external_pc_login_url'));
    }

    if ($request->isSmartphone())
    {
      $gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('smartphoneLogin');
      $this->contentsGadgets = $gadgets['smartphoneLoginContents'];

      $this->setLayout('smtLayoutSns');
      $this->setTemplate('smtLogin');    
    }
    else
    {
      $this->gadgetConfig = sfConfig::get('sa_login_gadget_list');
      $gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('login');
      $layout = Doctrine::getTable('SiteConfig')->get('login_layout', 'layoutA');
      $this->setLayout($layout);

      switch($layout)
      {
        case 'layoutA' :
          $this->topGadgets = $gadgets['loginTop'];
        case 'layoutB' :
          $this->sideMenuGadgets = $gadgets['loginSideMenu'];
      }

      $this->contentsGadgets = $gadgets['loginContents'];
      $this->bottomGadgets = $gadgets['loginBottom'];
    }

    return parent::executeLogin($request);
  }


 /**
  * Executes profile action
  *
  * @param saWebRequest $request A request object
  */
  public function executeProfile(saWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'member', 'smtProfile');

    $id = $request->getParameter('id', $this->getUser()->getMemberId());
    if ($id != $this->getUser()->getMemberId())
    {
      sfConfig::set('sf_nav_type', 'friend');
    }

    $this->gadgetConfig = sfConfig::get('sa_profile_gadget_list');

    $gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('profile');
    $layout = Doctrine::getTable('SiteConfig')->get('profile_layout', 'layoutA');
    $this->setLayout($layout);

    switch ($layout)
    {
      case 'layoutA' :
        $this->topGadgets = $gadgets['profileTop'];
      case 'layoutB' :
        $this->sideMenuGadgets = $gadgets['profileSideMenu'];
    }
    $this->contentsGadgets = $gadgets['profileContents'];
    $this->bottomGadgets = $gadgets['profileBottom'];

    return parent::executeProfile($request);
  }

 /**
  * Executes smtProfile action
  *
  * @param saWebRequest $request A request object
  */
  public function executeSmtProfile(saWebRequest $request)
  {
    $gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('smartphoneProfile');
    $this->contentsGadgets = $gadgets['smartphoneProfileContents'];

    $result = parent::executeProfile($request);

    saSmartphoneLayoutUtil::setLayoutParameters(array('member' => $this->member));

    return $result;
  }

 /**
  * Executes configImage action
  *
  * @param saWebRequest $request A request object
  */
  public function executeConfigImage(saWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'member', 'smtConfigImage');

    $options = array('member' => $this->getUser()->getMember());
    $this->form = new MemberImageForm(array(), $options);

    if ($request->isMethod(sfWebRequest::POST))
    {
      try
      {
        if (!$this->form->bindAndSave($request->getParameter('member_image'), $request->getFiles('member_image')))
        {
          $errors = $this->form->getErrorSchema()->getErrors();
          if (isset($errors['file']))
          {
            $error = $errors['file'];
            $i18n = $this->getContext()->getI18N();
            $this->getUser()->setFlash('error', $i18n->__($error->getMessageFormat(), $error->getArguments()));
          }
        }
      }
      catch (saRuntimeException $e)
      {
        $this->getUser()->setFlash('error', $e->getMessage());
      }
      $this->redirect('@member_config_image');
    }

  }


 /**
  * Executes smtCofigImage action
  *
  * @param saWebRequest $request A request object
  */

  public function executeSmtConfigImage(saWebRequest $request)
  {
    $options = array('member' => $this->getUser()->getMember());
    $this->form = new MemberImageForm(array(), $options);

    if ($request->isMethod(sfWebRequest::POST))
    {
      try
      {
        if (!$this->form->bindAndSave($request->getParameter('member_image'), $request->getFiles('member_image')))
        {
          $errors = $this->form->getErrorSchema()->getErrors();
          if (isset($errors['file']))
          {
            $error = $errors['file'];
            $i18n = $this->getContext()->getI18N();
            $this->getUser()->setFlash('error', $i18n->__($error->getMessageFormat(), $error->getArguments()));
          }
        }
      }
      catch (saRuntimeException $e)
      {
        $this->getUser()->setFlash('error', $e->getMessage());
      }
      $this->redirect('@member_config_image');
    }

    return parent::executeConfigImage($request);
  }

 /**
  * Executes configJsonApi action
  *
  * @param sfRequest $request A request object
  */
  public function executeConfigJsonApi(saWebRequest $request)
  {
    $this->forward404Unless(saConfig::get('enable_jsonapi'));

    $member = $this->getUser()->getMember();

    if (isset($request['reset_api_key']) && '1' === $request['reset_api_key'])
    {
      $request->checkCSRFProtection();
      $member->generateApiKey();
    }

    $this->apiKey = $member->getApiKey();

    return sfView::SUCCESS;
  }

 /**
  * Executes registerMobileToRegisterEnd action
  *
  * @param saWebRequest $request A request object
  */
  public function executeRegisterMobileToRegisterEnd(saWebRequest $request)
  {
    saActivateBehavior::disable();
    $this->form = new registerMobileForm($this->getUser()->getMember());
    saActivateBehavior::enable();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('member_config'));
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->redirect('member/registerMobileToRegisterEndFinish');
      }
    }

    return sfView::SUCCESS;
  }

  public function executeRegisterMobileToRegisterEndFinish(saWebRequest $request)
  {
  }

  /**
   * Executes changeLanguage action
   *
   * @param saWebRequest $request a request object
   */
  public function executeChangeLanguage(saWebRequest $request)
  {
    if ($request->isMethod(sfWebRequest::POST))
    {
      $form = new saLanguageSelecterForm();
      if ($form->bindAndSetCulture($request->getParameter('language')))
      {
        $this->redirect($form->getValue('next_uri'));
      }
    }
    $this->redirect('@homepage');
  }


 /**
  * Executes editConfig action
  *
  * @param saWebRequest $request a request object
  */
  public function executeConfig(saWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'member', 'smtConfig');

    return parent::executeConfig($request);
  }

 /**
  * Executes editSmtConfig action
  *
  * @param saWebRequest $request a request object
  */
  public function executeSmtConfig(saWebRequest $request)
  {
    return parent::executeConfig($request);
  }

 /**
  * Executes search action
  *
  * @param saWebRequest $request a request object
  */
  public function executeSearch(saWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'member', 'smtSearch');

    return parent::executeSearch($request);
  }

 /**
  * Executes smtSearch action
  *
  * @param saWebRequest $request a request object
  */
  public function executeSmtSearch(saWebRequest $request)
  {
    return sfView::SUCCESS;
  }

 /**
  * Executes invite action
  *
  * @param saWebRequest $request a request object
  */
  public function executeInvite(saWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'member', 'smtInvite');

    return parent::executeInvite($request);
  }


 /**
  * Executes editSmtConfig action
  *
  * @param saWebRequest $request a request object
  */
  public function executeSmtInvite(saWebRequest $request)
  {
    return parent::executeInvite($request);
  }


 /**
  * Executes editProfile action
  *
  * @param saWebRequest $request a request object
  */
  public function executeEditProfile(saWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'member', 'smtEditProfile');

    return parent::executeEditProfile($request);
  }

 /**
  * Executes smtEditProfile action
  *
  * @param saWebRequest $request a request object
  */
  public function executeSmtEditProfile(saWebRequest $request)
  {
    return parent::executeEditProfile($request);
  }
}
