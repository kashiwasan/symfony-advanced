<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saAuthLoginForm represents a form to login.
 *
 * @package    SfAdvanced
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class saAuthLoginForm extends BaseForm
{
  protected
    $adapter = null;

  const AUTH_MODE_FIELD_NAME = 'authMode';

  /**
   * Constructor.
   *
   * @param saAuthAdapter $adapter    An saAuthAdapter object
   * @param array         $defaults   An array of field default values
   * @param array         $options    An array of options
   *
   * @see sfForm
   */
  public function __construct(saAuthAdapter $adapter, $defaults = array(), $options = array())
  {
    $this->adapter = $adapter;

    parent::__construct($defaults, $options, false);

    $this->setWidget('next_uri', new saWidgetFormInputHiddenNextUri());
    $this->setValidator('next_uri', new saValidatorNextUri());

    if ($this->getOption('is_use_remember_me', true) && !sfConfig::get('app_is_mobile'))
    {
      $this->setWidget('is_remember_me', new sfWidgetFormInputCheckbox());
      $this->setValidator('is_remember_me', new sfValidatorBoolean());
      $this->widgetSchema->setLabel('is_remember_me', 'Remember me');
    }

    $this->widgetSchema->setNameFormat('auth'.$this->adapter->getAuthModeName().'[%s]');
  }

  /**
   * Returns the name of current authMode.
   *
   * @return string
   */
  public function getAuthMode()
  {
    return $this->adapter->getAuthModeName();
  }

  /**
   * Returns the current authentication adapter
   *
   * @return string
   */
  public function getAuthAdapter()
  {
    return $this->adapter;
  }

  /**
   * Returns the logined member.
   *
   * @return Member
   */
  public function getMember()
  {
    $member = $this->getValue('member');
    if ($member instanceof Member)
    {
      return $member;
    }

    return false;
  }

 /**
  * @todo removes this method.
  */
  public function isUtn()
  {
    return false;
  }
}
