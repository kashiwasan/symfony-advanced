<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * SfAdvanced_Sniffs_NamingConventions_ValidClassPropertySniff
 *
 * @package    SfAdvanced
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class SfAdvanced_Sniffs_NamingConventions_ValidClassPropertySniff extends PHP_CodeSniffer_Standards_AbstractVariableSniff
{
  protected function processMemberVar(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    $tokens = $phpcsFile->getTokens();

    $name = $tokens[$stackPtr]['content'];
    if (!PHP_CodeSniffer::isCamelCaps(substr($name, 1), false, true, false))
    {
      $error = $name.' name is not in camel caps format';
      $phpcsFile->addError($error, $stackPtr);
    }
  }

  protected function processVariable(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    return null;
  }

  protected function processVariableInString(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    return null;
  }
}
