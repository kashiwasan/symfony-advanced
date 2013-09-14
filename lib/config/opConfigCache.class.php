<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saConfigCache
 *
 * @package    SfAdvanced
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saConfigCache extends sfConfigCache
{
  /**
   * @see sfConfigCache
   */
  public function registerConfigHandler($handler, $class, $params = array())
  {
    $handler = str_replace(DIRECTORY_SEPARATOR, '/', $handler);
    parent::registerConfigHandler($handler, $class, $params);
  }

  /**
   * @see sfConfigCache
   */
  protected function writeCacheFile($config, $cache, $data)
  {
    parent::writeCacheFile($config, $cache, $data);
    @chmod($cache, 0666);
  }
}
