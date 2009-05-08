<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class defaultComponents extends sfComponents
{
  public function executeGlobalNav()
  {
    $type = 'insecure_global';
    if (opToolkit::isSecurePage()) {
      $type = 'secure_global';
    }
    $this->navs = Doctrine::getTable('Navigation')->retrieveByType($type);
  }

  public function executeLocalNav()
  {
    $isSecurePage = opToolkit::isSecurePage();
    if (!opToolkit::isSecurePage()) {
      return sfView::NONE;
    }

    $context = sfContext::getInstance();
    $module = $context->getActionStack()->getLastEntry()->getModuleName();
    $action = $context->getActionStack()->getLastEntry()->getActionName();

    $this->type = sfConfig::get('sf_nav_type', sfConfig::get('mod_' . $module . '_default_nav', 'default'));

    $this->navs = Doctrine::getTable('Navigation')->retrieveByType($this->type);

    if ('default' !== $this->type)
    {
      $this->navId = sfConfig::get('sf_nav_id', $context->getRequest()->getParameter('id'));
    }
  }

  public function executeSideBannerGadgets()
  {
    $gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('sideBanner');
    $this->gadgets = $gadgets['sideBannerContents'];
  }

  public function executeInformationBox()
  {
    $this->information = Doctrine::getTable('SnsConfig')->get('pc_home_information');
  }

  public function executeFreeAreaBox()
  {
  }

  public function executeMemberImageBox()
  {
  }

  public function executeSearchBox()
  {
    $this->searchActions = array(
      'Member' => 'member',
      'Community' => 'community',
    );
  }

  public function executeLanguageSelecterBox()
  {
    $this->form = new opLanguageSelecterForm();
  }

  public function executeLoginFormBox()
  {
    $this->forms = $this->getUser()->getAuthForms();
  }
}
