<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * NotificationMail
 *
 * @package    SfAdvanced
 * @subpackage model
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class NotificationMail extends BaseNotificationMail
{
  public function __toString()
  {
    return $this->Translation[self::getDefaultCulture()]->template;
  }
}
