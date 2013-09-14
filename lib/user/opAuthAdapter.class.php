<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saAuthAdapter will handle authentication for SfAdvanced.
 *
 * @package    SfAdvanced
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class saAuthAdapter
{
  protected
    $authModuleName = '',
    $authModeName = '',
    $authForm = null,

    $authLoginForm = null;

  public function __construct($name)
  {
    $this->setAuthModeName($name);
    $formClass = self::getAuthFormClassName($this->authModeName);
    if (class_exists($formClass))
    {
      $this->authForm = new $formClass($this);
    }

    $this->configure();
  }

  public function configure()
  {
  }

  public function getAuthParameters()
  {
    $params = $this->getRequest()->getParameter('auth'.$this->authModeName);
    return $params;
  }

  public function getAuthConfigSettings($name = '')
  {
    if (!sfConfig::has('sa_auth_'.$this->getAuthModeName()))
    {
      // default
      $configPath = sfConfig::get('sf_lib_dir').'/config/config/auth.yml';
      sfContext::getInstance()->getConfigCache()->registerConfigHandler($configPath, 'sfSimpleYamlConfigHandler', array());
      $default = include(sfContext::getInstance()->getConfigCache()->checkConfig($configPath));

      // plugins
      $configPath = sfConfig::get('sf_plugins_dir').'/saAuth'.$this->getAuthModeName().'Plugin/config/auth.yml';
      sfContext::getInstance()->getConfigCache()->registerConfigHandler($configPath, 'sfSimpleYamlConfigHandler', array());
      $plugins = include(sfContext::getInstance()->getConfigCache()->checkConfig($configPath));

      sfConfig::set('sa_auth_'.$this->getAuthModeName(), sfToolkit::arrayDeepMerge($default, $plugins));
    }

    $configs = sfConfig::get('sa_auth_'.$this->getAuthModeName());
    if (!$name)
    {
      return $configs;
    }
    elseif (!empty($configs[$name]))
    {
      return $configs[$name];
    }

    return null;
  }

  public function getAuthConfig($name)
  {
    $setting = $this->getAuthConfigSettings($name);
    if (!$setting)
    {
      return null;
    }

    if (!isset($setting['Default']))
    {
      $setting['Default'] = null;
    }

    if (isset($setting['IsConfig']) && !$setting['IsConfig'])
    {
      return $setting['Default'];
    }

    return Doctrine::getTable('SnsConfig')->get('sa_auth_'.$this->authModeName.'_plugin_'.$name, $setting['Default']);
  }

  public function setAuthConfig($name, $value)
  {
    $setting = $this->getAuthConfigSettings($name);
    if (!$setting)
    {
      return null;
    }

    if (isset($setting['IsConfig']) && !$setting['IsConfig'])
    {
      return false;
    }

    return Doctrine::getTable('SnsConfig')->set('sa_auth_'.$this->authModeName.'_plugin_'.$name, $value);
  }

  public function getAuthForm($forceAuthForm = false)
  {
    $form = $this->getAuthLoginForm();
    if ($form && !$forceAuthForm)
    {
      return $form;
    }

    sfContext::getInstance()->getConfiguration()->getEventDispatcher()->notify(new sfEvent(null, 'application.log', array('The '.self::getAuthFormClassName($this->authModeName).' is deprecated. Please create the class is named '.self::getAuthLoginFormClassName($this->authModeName), 'priority' => sfLogger::ERR)));

    return $this->authForm;
  }

  public function getAuthLoginForm()
  {
    if (!$this->authLoginForm)
    {
      $formClass = self::getAuthLoginFormClassName($this->authModeName);
      if (class_exists($formClass))
      {
        $this->authLoginForm = new $formClass($this);
      }
    }

    return $this->authLoginForm;
  }

  public function getAuthConfigForm()
  {
    $form = null;

    $formClass = self::getAuthConfigFormClassName($this->authModeName);
    if (class_exists($formClass))
    {
      $form = new $formClass($this);
    }

    return $form;
  }

  public function getAuthRegisterForm()
  {
    $form = null;

    $formClass = self::getAuthRegisterFormClassName($this->authModeName);
    $member = sfContext::getInstance()->getUser()->getMember();

    if (class_exists($formClass))
    {
      $form = new $formClass(array(), array('member' => $member));
    }
    // deprecated
    else
    {
      $form = $this->getAuthForm(true);
      $form->setForRegisterWidgets($member);
      sfContext::getInstance()->getConfiguration()->getEventDispatcher()->notify(new sfEvent(null, 'application.log', array('The '.self::getAuthFormClassName($this->authModeName).' is deprecated. Please create the class is named '.self::getAuthRegisterFormClassName($this->authModeName), 'priority' => sfLogger::ERR)));
    }

    return $form;
  }

  public function authenticate()
  {
    $authForm = $this->getAuthForm();
    $authForm->bind($this->getAuthParameters());
    if ($authForm->isValid())
    {
      if ($member = $authForm->getMember())
      {
        return $member->getId();
      }
    }

    return false;
  }

  public static function getAuthRegisterFormClassName($authMode)
  {
    return 'saAuthRegisterForm'.ucfirst($authMode);
  }

  public static function getAuthLoginFormClassName($authMode)
  {
    return 'saAuthLoginForm'.ucfirst($authMode);
  }

  public static function getAuthConfigFormClassName($authMode)
  {
    return 'saAuthConfigForm'.ucfirst($authMode);
  }

 /**
  * @deprecated
  */
  public static function getAuthFormClassName($authMode)
  {
    return 'saAuthForm_'.$authMode;
  }

 /**
  * Gets name of this authentication method
  */
  public function getAuthModeName()
  {
    return $this->authModeName;
  }

 /**
  * Names this authentication method.
  *
  * @param string $name
  */
 public function setAuthModeName($name)
 {
   $this->authModeName = $name;
 }

 /**
  * Gets sfRequest instance.
  *
  * @return sfRequest
  */
  protected function getRequest()
  {
    return sfContext::getInstance()->getRequest();
  }

  /**
   * Registers data to storage container.
   *
   * @deprecated
   *
   * @param  int    $memberId
   * @param  sfForm $form
   *
   * @return bool   true if the data has already been saved, false otherwise
   */
  public function registerData($memberId, $form)
  {
  }

 /**
  * Registers the current user with SfAdvanced
  *
  * @param  sfForm $form
  * @return bool   returns true if the current user is authenticated, false otherwise
  */
  public function register($form)
  {
    if ($form instanceof saAuthRegisterForm)
    {
      return $form->save();
    }

    $member = true;
    $profile = true;

    if ($form->memberForm) {
      $member = $form->memberForm->save();
      $memberId = $member->getId();
    }

    if ($form->profileForm) {
      $profile = $form->profileForm->save($memberId);
    }

    if ($form->configForm) {
      $config = $form->configForm->save($memberId);
    }

    $auth = $this->registerData($memberId, $form);

    if ($member && $profile && $auth && $config) {
      return $memberId;
    }

    return false;
  }

 /**
  * Activates the member
  */
  public function activate()
  {
    saActivateBehavior::disable();
    $member = sfContext::getInstance()->getUser()->getMember();
    $member->setIsActive(true);
    $result = $member->save();
    saActivateBehavior::enable();
    return $result;
  }

  /**
   * Returns true if the current state is a beginning of register.
   *
   * @return bool returns true if the current state is a beginning of register, false otherwise
   */
  abstract public function isRegisterBegin($member_id = null);

  /**
   * Returns true if the current state is a end of register.
   *
   * @return bool returns true if the current state is a end of register, false otherwise
   */
  abstract public function isRegisterFinish($member_id = null);

  /**
   * Gets an action path to begin registration
   *
   * @return string
   */
  public function getRegisterInputAction($token)
  {
    return 'member/registerInput?token='.$token;
  }

  /**
   * Gets an action path to register
   *
   * @return string
   */
  public function getRegisterEndAction($token)
  {
    return $this->authModuleName.'/registerEnd?token='.$token;
  }

  public function getAuthModuleName()
  {
    return $this->authModuleName;
  }

  public function isInvitedRegisterable()
  {
    // is this adapter allowed registration?
    if (!in_array($this->getAuthModeName(), sfContext::getInstance()->getUser()->getAuthModes()))
    {
      return false;
    }

    $member = sfContext::getInstance()->getUser()->getMember(true);

    if ($member->getConfig('is_admin_invited', false) && $this->getAuthModeName() !== $member->getConfig('register_auth_mode'))
    {
      return false;
    }

    return true;
  }
}
