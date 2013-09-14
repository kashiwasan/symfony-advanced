<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saPassword form.
 *
 * @package    SfAdvanced
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class saPasswordForm extends BaseForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'password' => new sfWidgetFormInputPassword(),
    ));

    $this->setValidators(array(
      'password' => new sfValidatorAnd(array(
        new sfValidatorString(),
        new sfValidatorCallback(array('callback' => array($this, 'isValidPassword')))
      ))
    ));

    $this->widgetSchema->setNameFormat('password[%s]');
  }

  public function isValidPassword($validator, $value)
  {
    $member = $this->sations['member'];
    if (md5($value) !== Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('password', $member->getId())->getValue())
    {
      throw new sfValidatorError(new sfValidatorPass(), 'invalid', array('value' => $value));
    }

    return $value;
  }
}
