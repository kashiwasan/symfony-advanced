<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * activity actions.
 *
 * @package    SfAdvanced
 * @subpackage action
 * @author     Kimura Youichi <kim.upsilon@gmail.com>
 */
class activityActions extends saJsonApiActions
{
  public function executeSearch(sfWebRequest $request)
  {
    $builder = saActivityQueryBuilder::create()
      ->setViewerId($this->getUser()->getMemberId());

    if (isset($request['target']))
    {
      if ('friend' === $request['target'])
      {
        $builder->includeFriends($request['target_id'] ? $request['target_id'] : null);
      }
      elseif ('community' === $request['target'])
      {
        $this->forward400Unless($request['target_id'], 'target_id parameter not specified.');
        $builder
          ->includeSelf()
          ->includeFriends()
          ->includeSns()
          ->setCommunityId($request['target_id']);
      }
      else
      {
        $this->forward400('target parameter is invalid.');
      }
    }
    else
    {
      if (isset($request['member_id']))
      {
        $builder->includeMember($request['member_id']);
      }
      else
      {
        $builder
          ->includeSns()
          ->includeFriends()
          ->includeSelf();
      }
    }

    $query = $builder->buildQuery();

    if (isset($request['keyword']))
    {
      $query->andWhereLike('body', $request['keyword']);
    }

    $globalAPILimit = sfConfig::get('sa_json_api_limit', 20);
    if (isset($request['count']) && (int)$request['count'] < $globalAPILimit)
    {
      $query->limit($request['count']);
    }
    else
    {
      $query->limit($globalAPILimit);
    }

    if (isset($request['max_id']))
    {
      $query->addWhere('id <= ?', $request['max_id']);
    }

    if (isset($request['since_id']))
    {
      $query->addWhere('id > ?', $request['since_id']);
    }

    if (isset($request['activity_id']))
    {
      $query->addWhere('id = ?', $request['activity_id']);
    }

    $this->activityData = $query
      ->andWhere('in_reply_to_activity_id IS NULL')
      ->execute();

    $this->setTemplate('array');
  }

  public function executeMember(sfWebRequest $request)
  {
    if ($request['id'])
    {
      $request['member_id'] = $request['id'];
    }

    if (isset($request['target']))
    {
      unset($request['target']);
    }

    $this->forward('activity', 'search');
  }

  public function executeFriends(sfWebRequest $request)
  {
    $request['target'] = 'friend';

    if (isset($request['member_id']))
    {
      $request['target_id'] = $request['member_id'];
      unset($request['member_id']);
    }
    elseif (isset($request['id']))
    {
      $request['target_id'] = $request['id'];
      unset($request['id']);
    }

    $this->forward('activity', 'search');
  }

  public function executeCommunity(sfWebRequest $request)
  {
    $request['target'] = 'community';

    if (isset($request['community_id']))
    {
      $request['target_id'] = $request['community_id'];
      unset($request['community_id']);
    }
    elseif (isset($request['id']))
    {
      $request['target_id'] = $request['id'];
      unset($request['id']);
    }
    else
    {
      $this->forward400('community_id parameter not specified.');
    }

    $this->forward('activity', 'search');
  }

  public function executePost(sfWebRequest $request)
  {
    $body = (string)$request['body'];
    $this->forward400If('' === $body, 'body parameter not specified.');
    $this->forward400If(mb_strlen($body) > 140, 'The body text is too long.');

    $memberId = $this->getUser()->getMemberId();
    $sations = array();

    if (isset($request['public_flag']))
    {
      $sations['public_flag'] = $request['public_flag'];
    }

    if (isset($request['in_reply_to_activity_id']))
    {
      $sations['in_reply_to_activity_id'] = $request['in_reply_to_activity_id'];
    }

    if (isset($request['uri']))
    {
      $sations['uri'] = $request['uri'];
    }
    elseif (isset($request['url']))
    {
      $sations['uri'] = $request['url'];
    }

    if (isset($request['target']) && 'community' === $request['target'])
    {
      if (!isset($request['target_id']))
      {
        $this->forward400('target_id parameter not specified.');
      }

      $sations['foreign_table'] = 'community';
      $sations['foreign_id'] = $request['target_id'];
    }

    $sations['source'] = 'API';

    $imageFiles = $request->getFiles('images');
    if (!empty($imageFiles))
    {
      foreach ((array)$imageFiles as $imageFile)
      {
        $validator = new saValidatorImageFile(array('required' => false));
        try
        {
          $obj = $validator->clean($imageFile);
        }
        catch (sfValidatorError $e)
        {
          $this->forward400('This image file is invalid.');
        }
        if (is_null($obj))
        {
          continue; // empty value
        }
        $file = new File();
        $file->setFromValidatedFile($obj);
        $file->setName('ac_'.$this->getUser()->getMemberId().'_'.$file->getName());
        $file->save();
        $sations['images'][]['file_id'] = $file->getId();
      }
    }

    $this->activity = Doctrine::getTable('ActivityData')->updateActivity($memberId, $body, $sations);

    if ('1' === $request['forceHtml'])
    {
      // workaround for some browsers (see #3201)
      $this->getRequest()->setRequestFormat('html');
      $this->getResponse()->setContentType('text/html');
    }

    $this->setTemplate('object');
  }

  public function executeDelete(sfWebRequest $request)
  {
    if (isset($request['activity_id']))
    {
      $activityId = $request['activity_id'];
    }
    elseif (isset($request['id']))
    {
      $activityId = $request['id'];
    }
    else
    {
      $this->forward400('activity_id parameter not specified.');
    }

    $activity = Doctrine::getTable('ActivityData')->find($activityId);

    $this->forward404Unless($activity, 'Invalid activity id.');

    $this->forward403Unless($activity->getMemberId() === $this->getUser()->getMemberId());

    $activity->delete();

    return $this->renderJSON(array('status' => 'success'));
  }

  public function executeMentions(sfWebRequest $request)
  {
    $builder = saActivityQueryBuilder::create()
      ->setViewerId($this->getUser()->getMemberId())
      ->includeMentions();

    $query = $builder->buildQuery()
      ->andWhere('in_reply_to_activity_id IS NULL')
      ->andWhere('foreign_table IS NULL')
      ->andWhere('foreign_id IS NULL')
      ->limit(20);

    $this->activityData = $query->execute();

    $this->setTemplate('array');
  }
}
