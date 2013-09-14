<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saParameterHolder
 *
 * @package    SfAdvanced
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 * @see        sfParameterHolder
 */
class saParameterHolder extends sfParameterHolder
{
  public function & get($name, $default = null, $isStripNullbyte = true)
  {
    if ($isStripNullbyte)
    {
      $value = saToolkit::stripNullByteDeep(parent::get($name, $default));
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
      $value = saToolkit::stripNullByteDeep(parent::getAll());
    }
    else
    {
      $value = & parent::getAll();
    }

    return $value;
  }
}
