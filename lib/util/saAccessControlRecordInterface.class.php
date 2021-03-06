<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saAccessControlRecordInterface
 *
 * @package    SfAdvanced
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
interface saAccessControlRecordInterface
{
 /**
  * Generates and returns role ID that is specified the instance of Zend_Acl_Role.
  *
  * It generates a role of the specified member that is from the record.
  *
  * @return string
  */
  public function generateRoleId(Member $member);
}
