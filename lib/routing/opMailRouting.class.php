<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMailRouting
 *
 * @package    SfAdvanced
 * @subpackage routing
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opMailRouting extends sfPatternRouting
{
  protected function fixGeneratedUrl($url, $absolute = false)
  {
    if ('/' === $url[0])
    {
      $url = substr($url, 1);
    }

    return $url.'@'.sfConfig::get('op_mail_domain');
  }
}
