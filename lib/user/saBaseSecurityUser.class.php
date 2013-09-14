<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * The base class of the all security user classes for SfAdvanced.
 *
 * @package    SfAdvanced
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
abstract class saBaseSecurityUser extends sfBasicSecurityUser
{
  const SITE_IDENTIFIER_NAMESPACE = 'SfAdvanced/user/saSecurityUser/site_identifier';

  public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $sations = array())
  {
    if (!isset($sations['session_namespaces']))
    {
      $sations['session_namespaces'] = array(
        self::SITE_IDENTIFIER_NAMESPACE,
        self::LAST_REQUEST_NAMESPACE,
        self::AUTH_NAMESPACE,
        self::CREDENTIAL_NAMESPACE,
        self::ATTRIBUTE_NAMESPACE,
      );
    }

    $sessionConfig = sfConfig::get('sa_session_life_time');
    if (!empty($sessionConfig[sfConfig::get('sf_app')]['idletime']))
    {
      $sations['timeout'] = $sessionConfig[sfConfig::get('sf_app')]['idletime'];
    }

    parent::initialize($dispatcher, $storage, $sations);

    if (!$this->isValidSiteIdentifier())
    {
      // This session is not for this site.
      $this->logout();

      // So we need to clear all data of the current session because they might be tainted by attacker.
      // If SfAdvanced uses that tainted data, it may cause limited session fixation attack.
      $this->clearSessionData();

      return null;
    }
  }

  abstract public function logout();

  public function clearSessionData()
  {
    // remove data in storage
    foreach ($this->sations['session_namespaces'] as $v)
    {
      $this->storage->remove($v);
    }

    // remove attribtues
    $this->attributeHolder->clear();
  }

  public function isValidSiteIdentifier()
  {
    if (!sfConfig::get('sa_check_session_site_identifier', true))
    {
      return true;
    }

    $identifier = $this->storage->read(self::SITE_IDENTIFIER_NAMESPACE);

    return (is_null($identifier) || $this->generateSiteIdentifier() === $identifier);
  }

  public function generateSiteIdentifier()
  {
    $defaultBaseUrl = 'http://example.com';
    $identifier = sfConfig::get('sa_base_url', $defaultBaseUrl);

    if (0 === strpos($identifier, $defaultBaseUrl))
    {
      $request = sfContext::getInstance()->getRequest();
      $identifier = $request->getUriPrefix().$request->getRelativeUrlRoot();
    }

    return $identifier;
  }

  public function shutdown()
  {
    $this->storage->write(self::SITE_IDENTIFIER_NAMESPACE, $this->generateSiteIdentifier());

    parent::shutdown();
  }
}
