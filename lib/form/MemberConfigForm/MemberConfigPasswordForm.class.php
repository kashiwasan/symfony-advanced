<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * MemberConfigPassword form.
 *
 * @package    SfAdvanced
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class MemberConfigPasswordForm extends MemberConfigForm
{
  protected $category = 'password';

  public function configure()
  {
    $this->setWidget('now_password', new sfWidgetFormInputPassword());
    $this->setValidator('now_password', new sfValidatorCallback(array('required' => true, 'callback' => array($this, 'isValidPassword'))));
    $this->widgetSchema->setLabel('now_password', 'Your current password');
  }

  public function isValidPassword($validator, $value)
  {
    $member = sfContext::getInstance()->getUser()->getMember();
    if (md5($value) !== Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('password', $member->getId())->getValue())
    {
      throw new sfValidatorError(new sfValidatorPass(), 'invalid', array('value' => $value));
    }

    return $value;
  }

  public function save()
  {
    unset($this->values['now_password']);

    parent::save();
  }
}
