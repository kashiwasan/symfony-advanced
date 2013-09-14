<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class BlacklistTable extends Doctrine_Table
{
  // TODO: use findOneByUid()
  public function retrieveByUid($mobileUid)
  {
    return $this->createQuery()
      ->where('uid = ?', $mobileUid)
      ->fetchOne();
  }
}
