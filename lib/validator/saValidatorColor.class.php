<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saValidatorColor validates hex.
 *
 * @package    SfAdvanced
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saValidatorColor extends saValidatorHex
{
  protected function doClean($value)
  {
    $value = str_replace('#', '', $value);

    if (!in_array(strlen($value), array(3, 6)))
    {
      throw new sfValidatorError($this, 'invalid');
    }

    $clean = parent::doClean($value);

    return '#'.$clean;
  }
}
