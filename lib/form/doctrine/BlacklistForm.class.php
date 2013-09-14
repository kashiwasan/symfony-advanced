<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Blacklist form.
 *
 * @package    SfAdvanced
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class BlacklistForm extends BaseBlacklistForm
{
  public function configure()
  {
    $this->setValidator('uid', new opValidatorHash());
    unset($this['created_at'], $this['updated_at']);
  }
}
