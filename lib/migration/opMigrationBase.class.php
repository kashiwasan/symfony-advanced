<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMigrationBase provides way to migrate
 *
 * @package    SfAdvanced
 * @subpackage migration
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opMigrationBase extends Doctrine_Migration_Base
{
  // for BC
  public function getProperty($name)
  {
    return $this->$name;
  }

  // for BC
  public function setProperty($name, $value)
  {
    $this->$name = $value;
  }
}
