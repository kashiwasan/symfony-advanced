<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * sfWidgetFormSchemaFormatterPc
 *
 * @package    SfAdvanced
 * @subpackage widget
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @author     Rimpei Ogawa <ogawa@tejimaya.com>
 */
class sfWidgetFormSchemaFormatterPc extends saWidgetFormSchemaFormatter
{
  protected
    $helpFormat            = '<div class="help">%help%</div>',
    $errorListFormatInARow = "  <div class=\"error\"><ul class=\"error_list\">\n%errors%  </ul></div>\n";
}
