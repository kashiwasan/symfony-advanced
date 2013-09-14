<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAuthMailAddressPasswordRecoveryForm
 *
 * @package    SfAdvanced
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opAuthMailAddressPasswordChangeForm extends BaseForm
{
  public $member = null;

  public function configure()
  {
    $this->setWidgets(array(
      'password' => new sfWidgetFormInputPassword(),
      'password_confirm' => new sfWidgetFormInputPassword(),
    ));

    $this->setValidators(array(
      'password' => new opValidatorString(),
      'password_confirm' => new opValidatorString(),
    ));

    $this->widgetSchema->setLabel('password_confirm', 'Password (Confirm)');
    $this->widgetSchema->setNameFormat('password_change[%s]');

    $this->validatorSchema->setPostValidator(
      new sfValidatorSchemaCompare('password', '===', 'password_confirm')
    );
  }

  public function save()
  {
    $this->member->setConfig('password', md5($this->getValue('password')));
  }
}


