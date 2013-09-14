<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saValidatorHex validates hex.
 *
 * @package    SfAdvanced
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saValidatorHex extends saValidatorString
{
  protected function doClean($value)
  {
    $zfValidator = new Zend_Validate_Hex();
    if (!$zfValidator->isValid($value))
    {
      throw new sfValidatorError($this, 'invalid');
    }

    return $value;
  }
}
