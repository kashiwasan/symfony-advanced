<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * SfAdvanced_Sniffs_WhiteSpace_ControlSignatureSniff
 *
 * @package    SfAdvanced
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class SfAdvanced_Sniffs_WhiteSpace_ControlSignatureSniff extends PHP_CodeSniffer_Standards_AbstractPatternSniff
{
  protected function getPatterns()
  {
    return array(
       'tryEOL *{EOL...}EOL *catch (...)EOL *{EOL',
       'doEOL *{EOL...}EOL *while (...);EOL',
       'while (...)EOL *{EOL',
       'for (...)EOL *{EOL',
       'if (...)EOL *{EOL',
       'foreach (...)EOL *{EOL',
       '} *EOLelseif (...)EOL *{EOL',
       '} *EOLelseEOL *{EOL',
    );
  }
}
