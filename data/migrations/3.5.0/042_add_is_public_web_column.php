
<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision42_AddIsPublicWebColumn extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->column($direction, 'profile', 'is_public_web', 'integer', '1', array(
      'comment' => 'Flag for adding public_flag for publishing to web',
      'default' => 0,
    ));
  }
}
