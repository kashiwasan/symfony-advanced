<?php

require_once 'OAuth.php';

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saOAuthServer
 *
 * @package    SfAdvanced
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saOAuthServer extends OAuthServer
{
  public function __construct($data_store)
  {
    parent::__construct($data_store);

    $this->add_signature_method(new OAuthSignatureMethod_HMAC_SHA1());
  }
}
