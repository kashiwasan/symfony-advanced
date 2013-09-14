<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class changeProfileValueMinMaxColumnType extends saMigration
{
  public function up()
  {
    $sation = array(
      'length'  => 32,
    );

    $this->changeColumn('profile', 'value_min', 'string', $sation);
    $this->changeColumn('profile', 'value_max', 'string', $sation);
  }

  public function postUp()
  {
    $birthday = ProfilePeer::retrieveByName('birthday');
    if ($birthday)
    {
      $birthday->setValueMin('-100years');
      $birthday->setValueMax('now');
      $birthday->save();
    }
  }

  public function down()
  {
    $sation = array(
      'length'  => 4,
    );

    $this->changeColumn('profile', 'value_min', 'integer', $sation);
    $this->changeColumn('profile', 'value_max', 'integer', $sation);

    $birthday = ProfilePeer::retrieveByName('birthday');
    if ($birthday)
    {
      $birthday->setValueMin(null);
      $birthday->setValueMax(null);
      $birthday->save();
    }
  }
}
