<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saRoutingConfigHandler
 *
 * @package    SfAdvanced
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saRoutingConfigHandler extends sfRoutingConfigHandler
{
  protected function parse($configFiles)
  {
    $result = parent::parse($configFiles);

    $name = 'symfony_default_routes';
    $sations = array(
      'name'  => $name,
    );

    $result[$name] = array('saSymfonyDefaultRouteCollection', array($sations));

   return $result;
  }
}
