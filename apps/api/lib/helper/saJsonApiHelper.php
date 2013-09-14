<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saJsonApiHelper
 *
 * @package    SfAdvanced
 * @subpackage helper
 * @author     Kimura Youichi <kim.upsilon@gmail.com>
 */
function sa_api_member($member)
{
  $viewMemberId = sfContext::getInstance()->getUser()->getMemberId();

  $memberImageFileName = $member->getImageFileName();
  if (!$memberImageFileName)
  {
    $memberImage = sa_image_path('no_image.gif', true);
  }
  else
  {
    $memberImage = sf_image_path($memberImageFileName, array('size' => '48x48'), true);
  }

  $relation = null;
  if ((string)$viewMemberId !== (string)$member->getId())
  {
    $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($viewMemberId, $member->getId());
  }

  $selfIntroduction = $member->getProfile('sa_preset_self_introduction', true);

  return array(
    'id' => $member->getId(),
    'profile_image' => $memberImage,
    'screen_name' => $member->getConfig('sa_screen_name', $member->getName()),
    'name' => $member->getName(),
    'profile_url' => sa_api_member_profile_url($member->getId()),
    'friend' => $relation ? $relation->isFriend() : false,
    'blocking' => $relation ? $relation->isAccessBlocked() : false,
    'self' => $viewMemberId === $member->getId(),
    'friends_count' => $member->countFriends(),
    'self_introduction' => $selfIntroduction ? (string)$selfIntroduction : null,
  );
}

function sa_api_member_profile_url($memberId)
{
  return app_url_for('pc_frontend', array('sf_route' => 'obj_member_profile', 'id' => $memberId), true);
}

function sa_api_activity($activity)
{
  $viewMemberId = sfContext::getInstance()->getUser()->getMemberId();
  $member = $activity->getMember();

  $images = array();
  foreach ($activity->getImages() as $activityImage)
  {
    if (!is_null($activityImage->file_id))
    {
      $images[] = array(
        'small_uri' => sf_image_path($activityImage->getFile(), array('size' => '48x48'), true),
        'full_uri' => sf_image_path($activityImage->getFile(), array(), true),
      );
    }
    else
    {
      $images[] = array(
        'small_uri' => $activityImage->uri,
        'full_uri' => $activityImage->uri,
      );
    }
  }

  return array(
    'id' => $activity->getId(),
    'member' => sa_api_member($member),
    'body' => $activity->getBody(),
    'body_html' => sa_activity_linkification(nl2br(sa_api_force_escape($activity->getBody()))),
    'uri' => $activity->getUri(),
    'source' => $activity->getSource(),
    'source_uri' => $activity->getSourceUri(),
    'images' => $images,
    'created_at' => date('r', strtotime($activity->getCreatedAt())),
  );
}

function sa_activity_linkification($body, $sations = array())
{
  $body = sa_auto_link_text($body);

  return preg_replace_callback('/(@+)([-._0-9A-Za-z]+)/', 'sa_activity_linkification_callback', $body);
}

function sa_activity_linkification_callback($matches)
{
  $at = $matches[1];
  $screenName = $matches[2];
  $screenNameConfig = Doctrine::getTable('MemberConfig')->createQuery()
    ->select('member_id')
    ->addWhere('name = "sa_screen_name"')
    ->addWhere('value = ?', $screenName)
    ->fetchOne(array(), Doctrine::HYDRATE_NONE);

  if ($screenNameConfig)
  {
    $memberId = $screenNameConfig[0];
    return link_to($at.$screenName, sa_api_member_profile_url($memberId), array('target' => '_blank'));
  }

  return $matches[0];
}

function sa_api_force_escape($text)
{
  if (!sfConfig::get('sf_escaping_strategy'))
  {
    // escape body even if escaping method is disabled.
    $text = sfOutputEscaper::escape(sfConfig::get('sf_escaping_method'), $text);
  }

  return $text;
}

function sa_api_community($community)
{
  $viewMemberId = sfContext::getInstance()->getUser()->getMemberId();

  $communityUrl = app_url_for('pc_frontend', array('sf_route' => 'community_home', 'id' => $community->getId()), true);

  $communityImageFileName = $community->getImageFileName();
  if (!$communityImageFileName)
  {
    $communityImage = sa_image_path('no_image.gif', true);
  }
  else
  {
    $communityImage = sf_image_path($communityImageFileName, array('size' => '48x48'), true);
  }

  $communityMember = Doctrine::getTable('CommunityMember')
    ->retrieveByMemberIdAndCommunityId($viewMemberId, $community->getId());

  return array(
    'id' => $community->getId(),
    'name' => $community->getName(),
    'category' => $community->getCommunityCategory() ? $community->getCommunityCategory()->getName() : null,
    'community_url' => $communityUrl,
    'community_image_url' => $communityImage,
    'joining' => $communityMember ? !$communityMember->getIsPre() : false,
    'admin' => $communityMember ? $communityMember->hasPosition('admin') : false,
    'sub_admin' => $communityMember ? $communityMember->hasPosition('sub_admin') : false,
    'created_at' => sa_api_date($community->getCreatedAt()),
    'admin_member' => sa_api_member($community->getAdminMember()),
    'member_count' => $community->countCommunityMembers(),
    'public_flag' => $community->getConfig('public_flag'),
    'register_policy' => $community->getConfig('register_policy'),
    'description' => $community->getConfig('description'),
  );
}

function sa_api_date($date)
{
  return gmdate('r', strtotime($date));
}

function sa_api_notification($notification)
{
  if ($notification['icon_url'])
  {
    $iconUrl = $notification['icon_url'];
  }
  else
  {
    if ('link' === $notification['category'])
    {
      $fromMember = Doctrine::getTable('Member')->find($notification['member_id_from']);
      $fromMemberImageFileName = $fromMember ? $fromMember->getImageFileName() : null;

      if ($fromMemberImageFileName)
      {
        $iconUrl = sf_image_path($fromMemberImageFileName, array('size' => '48x48'), true);
      }
    }
  }

  if (!$iconUrl)
  {
    $iconUrl = sa_image_path('no_image.gif', true);
  }
  elseif (false !== strpos('http://', $iconUrl))
  {
    $iconUrl = sf_image_path($iconUrl, array('size' => '48x48'), true);
  }

  return array(
    'id' => $notification['id'],
    'body' => sfContext::getInstance()->getI18N()->__($notification['body']),
    'category' => $notification['category'],
    'unread' => $notification['unread'],
    'created_at' => date('r', strtotime($notification['created_at'])),
    'icon_url' => $iconUrl,
    'url' => $notification['url'] ? url_for($notification['url'], array('abstract' => true)) : null,
    'member_id_from' => $notification['member_id_from'],
  );
}
