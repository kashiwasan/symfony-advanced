<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision16_addOAuthColumn extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->column($direction, 'oauth_consumer', 'using_apis', 'array', '', array(
      'comment' => 'API list that this consumer uses',
    ));
  }
}
