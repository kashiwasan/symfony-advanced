<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class AdminUser extends BaseAdminUser
{
  public function preSave($event)
  {
    $this->password = md5($this->password);
  }
}
