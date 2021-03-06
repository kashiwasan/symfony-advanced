<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saAclBuilder builds instances of the Zend_Acl by the specified conditions
 *
 * @package    SfAdvanced
 * @subpackage acl
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class saAclBuilder
{
  abstract static public function buildResource($resource, $targetMembers);
}
