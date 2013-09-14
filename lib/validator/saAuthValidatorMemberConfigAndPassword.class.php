<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saAuthValidatorMemberConfigAndPassword
 *
 * @package    SfAdvanced
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saAuthValidatorMemberConfigAndPassword extends saAuthValidatorMemberConfig
{
  /**
   * @see saAuthValidatorMemberConfig
   */
  protected function configure($sations = array(), $messages = array())
  {
    parent::configure($sations, $messages);
    $this->setMessage('invalid', 'ID or password is not a valid.');
  }

  /**
   * @see saAuthValidatorMemberConfig
   */
  protected function doClean($values)
  {
    saActivateBehavior::disable();
    $values = parent::doClean($values);

    if (empty($values['member']) || !($values['member'] instanceof Member))
    {
      throw new sfValidatorError($this, 'invalid');
      saActivateBehavior::enable();
    }

    $valid_password = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('password', $values['member']->getId())->getValue();
    saActivateBehavior::enable();
    if (md5($values['password']) !== $valid_password)
    {
      throw new sfValidatorError($this, 'invalid');
    }

    return $values;
  }
}
