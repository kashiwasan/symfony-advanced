<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * oauth actions.
 *
 * @package    SfAdvanced
 * @subpackage action
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class oauthActions extends saOAuthTokenAction
{
  protected function getTokenModelName()
  {
    return 'OAuthAdminToken';
  }
}
