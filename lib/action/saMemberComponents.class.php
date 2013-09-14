<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saMemberComponents
 *
 * @package    SfAdvanced
 * @subpackage action
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
abstract class saMemberComponents extends sfComponents
{
  public function executeActivityBox(sfWebRequest $request)
  {
    $id = $request->getParameter('id', $this->getUser()->getMemberId());
    $this->activities = Doctrine::getTable('ActivityData')->getActivityList($id, null, $this->gadget->getConfig('row'));
    $this->member = Doctrine::getTable('Member')->find($id);
    $this->isMine = ($id == $this->getUser()->getMemberId());
  }

  public function executeAllMemberActivityBox(sfWebRequest $request)
  {
    $this->activities = Doctrine::getTable('ActivityData')->getAllMemberActivityList($this->gadget->getConfig('row'));
    if ($this->gadget->getConfig('is_viewable_activity_form') && saConfig::get('is_allow_post_activity'))
    {
      $this->form = new ActivityDataForm();
    }
  }

  public function executeBirthdayBox(sfWebRequest $request)
  {
    $id = $request->getParameter('id', $this->getUser()->getMemberId());
    $birthday = Doctrine::getTable('MemberProfile')->getViewableProfileByMemberIdAndProfileName($id, 'sa_preset_birthday');
    $this->targetDay = $birthday ? saToolkit::extractTargetDay((string)$birthday) : false;
  }
}
