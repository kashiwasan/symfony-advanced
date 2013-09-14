<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saAPIRouteInterface is an interface for route class related with API
 *
 * This interface should be implemented by route classes that defines routing
 * rule for api frontend actions.
 * All methods in this interface are used for identifying an API.
 *
 * @package    SfAdvanced
 * @subpackage routing
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
interface saAPIRouteInterface
{
 /**
  * Gets an API name
  *
  * This method is for getting a name that is used for identifying an API
  * by SfAdvanced.
  *
  * @return string
  */
  public function getAPIName();

 /**
  * Gets an API caption
  *
  * This method is for getting a caption that is used for explaing an API.
  * That must be human-readable and be simple.
  *
  * @return string
  */
  public function getAPICaption();
}
