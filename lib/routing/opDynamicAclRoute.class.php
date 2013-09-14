<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saDynamicAclRoute
 *
 * @package    SfAdvanced
 * @subpackage routing
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saDynamicAclRoute extends sfDoctrineRoute
{
  protected
    $acl = null;

  public function getAcl()
  {
    return $this->acl;
  }

  public function getObject()
  {
    $result = parent::getObject();

    if (!$role = $this->getCurrentMemberId())
    {
      $role = 'alien';
    }

    if ($result instanceof saAccessControlRecordInterface)
    {
      if (!$result->isAllowed($this->getCurrentMember(), $this->sations['privilege']))
      {
        $this->handleRestriction();
      }
    }
    elseif (!$this->acl->isAllowed($this->getCurrentMemberId(), null, $this->sations['privilege']))
    {
      $this->handleRestriction();
    }

    return $result;
  }

  protected function handleRestriction()
  {
    if ($this->getCurrentMember() instanceof saAnonymousMember)
    {
      sfContext::getInstance()->getController()->forward('member', 'login');

      throw new sfStopException();
    }
    else
    {
      throw new sfError404Exception('You are not allowed access to this resource.');
    }
  }

  protected function getObjectForParameters($parameters)
  {
    $result = parent::getObjectForParameters($parameters);
    if (!$result)
    {
      return $result;
    }

    if (!$result instanceof saAccessControlRecordInterface)
    {
      $this->acl = call_user_func($this->getAclBuilderName().'::buildResource', $result, $this->getTargetMemberList());
    }

    return $result;
  }

  protected function getAclBuilderName()
  {
    return 'op'.$this->sations['model'].'AclBuilder';
  }

  protected function getCurrentMember()
  {
    $user = sfContext::getInstance()->getUser();

    if (!is_null($user) && $user instanceof saSecurityUser)
    {
      return $user->getMember();
    }

    return new saAnonymousMember();
  }

  protected function getCurrentMemberId()
  {
    $result = 0;
    $user = sfContext::getInstance()->getUser();

    if (!is_null($user) && $user instanceof saSecurityUser)
    {
      $result = $user->getMemberId();
    }

    return $result;
  }

  protected function getTargetMemberList()
  {
    $result = array();
    $user = sfContext::getInstance()->getUser();

    if (!is_null($user) && $user instanceof saSecurityUser)
    {
      $result[] = $user->getMember();
    }

    return $result;
  }
}
