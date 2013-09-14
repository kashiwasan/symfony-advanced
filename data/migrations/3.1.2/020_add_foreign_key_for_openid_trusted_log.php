<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision20_addForeignKeyForOpenIDTrustedLog extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->foreignKey($direction, 'openid_trust_log', 'openid_trust_log_member_id_member_id', array(
      'name' => 'openid_trust_log_member_id_member_id',
      'local' => 'member_id',
      'foreign' => 'id',
      'foreignTable' => 'member',
      'onUpdate' => '',
      'onDelete' => 'cascade',
    ));

    $this->index($direction, 'openid_trust_log', 'openid_trust_log_member_id', array(
      'fields' => array(
        0 => 'member_id',
      ),
    ));
  }
}
