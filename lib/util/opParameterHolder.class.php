<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opParameterHolder
 *
 * @package    SfAdvanced
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 * @see        sfParameterHolder
 */
class opParameterHolder extends sfParameterHolder
{
  public function & get($name, $default = null, $isStripNullbyte = true)
  {
    if ($isStripNullbyte)
    {
      $value = opToolkit::stripNullByteDeep(parent::get($name, $default));
    }
    else
    {
      $value = & parent::get($name, $default);
    }

    return $value;
  }

  public function & getAll($isStripNullbyte = true)
  {
    if ($isStripNullbyte)
    {
      $value = opToolkit::stripNullByteDeep(parent::getAll());
    }
    else
    {
      $value = & parent::getAll();
    }

    return $value;
  }
}
