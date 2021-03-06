<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saMailRequest
 *
 * @package    SfAdvanced
 * @subpackage request
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saMailRequest extends saWebRequest
{
  static protected
    $mailMessage = null;

  static public function setMailMessage($mailMessage)
  {
    self::$mailMessage = $mailMessage;
  }

  public function getPathInfo()
  {
    if (empty(self::$mailMessage))
    {
      throw new LogicException('You must specify the email message.');
    }

    $pieces = explode('@', self::$mailMessage->to);

    return array_shift($pieces);
  }

  public function getPathInfoArray()
  {
    if (!$this->pathInfoArray)
    {
      $this->pathInfoArray = array_merge(parent::getPathInfoArray(), self::$mailMessage->getHeaders());
    }

    return $this->pathInfoArray;
  }

  public function getRequestContext()
  {
    return array_merge(parent::getRequestContext(), array(
      'to_address'   => self::$mailMessage->to,
      'from_address' => self::$mailMessage->from,
    ));
  }

  public function getMailMessage()
  {
    return self::$mailMessage;
  }
}
