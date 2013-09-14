<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * form to change community's admin request
 *
 * @package    SfAdvanced
 * @subpackage form
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opChangeCommunityAdminRequestForm extends opBaseForm
{
  public function setup()
  {
    $this->widgetSchema->setNameFormat('admin_request[%s]');
  }
}
