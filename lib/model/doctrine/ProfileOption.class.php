<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class ProfileOption extends BaseProfileOption
{

}

class opProfileOptionEmulator extends ProfileOption
{
  public function setTableDefinition()
  {
    parent::setTableDefinition();

    foreach ($this->getTable()->getColumns() as $name => $column)
    {
      $this->mapValue($name);
    }
  }
}
