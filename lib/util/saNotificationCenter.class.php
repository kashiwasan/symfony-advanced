<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saNotificationCenter
 *
 * @package    SfAdvanced
 * @subpackage util
 * @author     Kimura Youichi <kim.upsilon@gmail.com>
 * @author     Shouta Kashiwagi <kashiwagi@tejimaya.com>
 */
class saNotificationCenter
{
  static public function notify(Member $from, Member $to, $body, array $sations = null)
  {
    $notificationItem = array(
      'id' => microtime(),
      'body' => $body,
      'member_id_from' => $from->getId(),
      'created_at' => time(),
      'unread' => true,
      'category' => $sations['category'] ? $sations['category'] : 'other',
      'url' => $sations['url'] ? $sations['url'] : null,
      'icon_url' => $sations['icon_url'] ? $sations['icon_url'] : null,
    );

    $notificationObject = Doctrine::getTable('MemberConfig')
      ->findOneByMemberIdAndName($to->getId(), 'notification_center');

    if (!$notificationObject)
    {
      $notificationObject = new MemberConfig();
      $notificationObject->setMemberId($to->getId());
      $notificationObject->setName('notification_center');

      $notifications = array();
    }
    else
    {
      $notifications = unserialize($notificationObject->getValue());
    }

    array_unshift($notifications, $notificationItem);
    $notificationLimit = sfConfig::get('sa_notification_limit', 20);

    if ($notificationLimit < count($notifications))
    {
      $notifications = array_slice($notifications, 0, $notificationLimit);
    }

    $notificationObject->setValue(serialize($notifications));
    $notificationObject->save();
    $notificationObject->free(true);
  }

  static public function setRead(Member $target, $notificationId)
  {
    $notificationObject = Doctrine::getTable('MemberConfig')
      ->findOneByMemberIdAndName($target->getId(), 'notification_center');

    if (!$notificationObject)
    {
      return false;
    }
    else
    {
      $notifications = unserialize($notificationObject->getValue());
    }

    $success = false;

    foreach ($notifications as &$notification)
    {
      if ($notificationId === $notification['id'])
      {
        $notification['unread'] = false;
        $success = true;
      }
    }
    unset($notification);

    $notificationObject->setValue(serialize($notifications));
    $notificationObject->save();
    $notificationObject->free(true);

    return $success;
  }

  static public function getNotifications(Member $member = null)
  {
    if (is_null($member))
    {
      $member = sfContext::getInstance()->getUser()->getMember();
    }

    $notificationObject = Doctrine::getTable('MemberConfig')
      ->findOneByMemberIdAndName($member->getId(), 'notification_center');

    if (!$notificationObject)
    {
      $notifications = array();
    }
    else
    {
      $notifications = unserialize($notificationObject->getValue());
      $notificationObject->free(true);
    }

    return $notifications;
  }
}
