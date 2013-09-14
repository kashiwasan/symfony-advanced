<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * SfAdvanced_Sniffs_NamingConventions_ValidClassFunctionNameSniff
 *
 * @package    SfAdvanced
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class SfAdvanced_Sniffs_NamingConventions_ValidClassFunctionNameSniff extends PHP_CodeSniffer_Standards_AbstractScopeSniff
{
  public function __construct()
  {
    parent::__construct(array(T_CLASS, T_INTERFACE), array(T_FUNCTION));
  }


  protected function processTokenWithinScope(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $currScope)
  {
    $className  = $phpcsFile->getDeclarationName($currScope);
    $methodName = $phpcsFile->getDeclarationName($stackPtr);

    if (!PHP_CodeSniffer::isCamelCaps($methodName, false, true, false))
    {
      $error = $className.'::'.$methodName.'() name is not in camel caps format';
      $phpcsFile->addError($error, $stackPtr);
    }
  }
}
