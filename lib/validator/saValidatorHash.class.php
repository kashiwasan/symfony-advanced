<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saValidatorHash validates hashes (MD5, sha1).
 *
 * @package    SfAdvanced
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saValidatorHash extends sfValidatorRegex
{
  protected $algorithms = array(
    'md5'  => array('length' => 32),
    'sha1' => array('length' => 40),
  );

  /**
   * @see sfValidatorRegex
   */
  protected function configure($sations = array(), $messages = array())
  {
    parent::configure($sations, $messages);

    $this->addOption('algorithm', 'md5');

    $this->setOption('pattern', '');
  }

  /**
   * @see sfValidatorString
   */
  protected function doClean($value)
  {
    if (!array_key_exists($this->getOption('algorithm'), $this->algorithms))
    {
      throw new LogicException(__CLASS__.' does not support this algorithm');
    }

    $algorithm = $this->algorithms[$this->getOption('algorithm')];
    $this->setOption('pattern', '/^[a-f0-9]{'.$algorithm['length'].'}$/i');

    return parent::doClean($value);
  }
}
