<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saValidatorSearchQueryString
 *
 * @package    SfAdvanced
 * @subpackage validator
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class saValidatorSearchQueryString extends sfValidatorString
{
 /**
  * Configures the current validator.
  *
  * Available sations:
  * 
  *   * demiliter_pattern
  *
  * @param array $sations  An array of sations
  * @param array $messages An array of messages
  *
  * @see sfValidatorString
  */
  protected function configure($sations = array(), $messages = array())
  {
    $this->addOption('demiliter_pattern', '/[\s　]+/u');

    parent::configure($sations, $messages);
  }

  protected function doClean($value)
  {
    $clean = parent::doClean($value);

    return preg_split($this->getOption('demiliter_pattern'), $clean, -1, PREG_SPLIT_NO_EMPTY);
  }
}
