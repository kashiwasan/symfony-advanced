<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saDoctrineFormatter
 *
 * @package    SfAdvanced
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class saDoctrineModule_Formatter extends Doctrine_Formatter
{
  public function getForeignKeyName($fkey)
  {
    $prefix = sfConfig::get('sa_table_prefix', '');
    if ($prefix && 0 !== strpos($fkey, $prefix))
    {
      $fkey = $prefix.$fkey;
    }

    return $fkey;
  }
}
