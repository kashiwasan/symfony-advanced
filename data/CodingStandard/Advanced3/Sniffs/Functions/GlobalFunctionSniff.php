<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * SfAdvanced_Sniffs_Functions_GlobalFunctionSniff
 *
 * @package    SfAdvanced
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class SfAdvanced_Sniffs_Functions_GlobalFunctionSniff extends Squiz_Sniffs_Functions_GlobalFunctionSniff
{
  public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    $tokens = $phpcsFile->getTokens();

    if (empty($tokens[$stackPtr]['conditions']) === true)
    {
      $functionName = $phpcsFile->getDeclarationName($stackPtr);

      if (false === strpos($phpcsFile->getFilename(), 'helper'))
      {
        $error = 'Defining functions is NOT permitted expecting helper functions.';
        $phpcsFile->addError($error, $stackPtr);
      }

      if (!PHP_CodeSniffer::isUnderscoreName($functionName))
      {
        $error = 'Function name must use underscores in accordance.'.$functionName;
        $phpcsFile->addError($error, $stackPtr);
      }
    }
  }
}
