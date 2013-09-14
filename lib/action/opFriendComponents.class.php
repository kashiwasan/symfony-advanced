<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saFriendComponents
 *
 * @package    SfAdvanced
 * @subpackage action
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
abstract class saFriendComponents extends sfComponents
{
  public function executeActivityBox()
  {
    $this->activities = Doctrine::getTable('ActivityData')->getFriendActivityList(null, $this->gadget->getConfig('row'));
    if (saConfig::get('is_allow_post_activity'))
    {
      $this->form = new ActivityDataForm();
    }
  }
}
