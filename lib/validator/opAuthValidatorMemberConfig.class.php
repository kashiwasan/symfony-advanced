<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saAuthValidatorMemberConfig
 *
 * @package    SfAdvanced
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saAuthValidatorMemberConfig extends sfValidatorSchema
{
  /**
   * Constructor.
   *
   * @param array  $sations   An array of sations
   * @param array  $messages  An array of error messages
   *
   * @see sfValidatorSchema
   */
  public function __construct($sations = array(), $messages = array())
  {
    parent::__construct(null, $sations, $messages);
  }

  /**
   * Configures this validator.
   *
   * Available sations:
   *
   *  * config_name: The configuration name of MemberConfig
   *
   * @see sfValidatorBase
   */
  protected function configure($sations = array(), $messages = array())
  {
    $this->addOption('field_name');
    $this->addOption('allow_empty_value', true);
    $this->addRequiredOption('config_name');
    $this->setMessage('invalid', 'ID is not a valid.');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($values)
  {
    saActivateBehavior::disable();
    $configName = $this->getOption('config_name');
    $fieldName = $this->getOption('field_name');
    if (!$fieldName)
    {
      $fieldName = $configName;
    }

    if (!$this->getOption('allow_empty_value') && empty($values[$fieldName]))
    {
      saActivateBehavior::enable();

      return $values;
    }

    $memberConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndValue($configName, $values[$fieldName]);
    if ($memberConfig)
    {
      $values['member'] = $memberConfig->getMember();
    }

    saActivateBehavior::enable();
    return $values;
  }
}
