<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saFrontWebController
 *
 * @package    SfAdvanced
 * @subpackage controller
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class saFrontWebController extends sfFrontWebController
{
  public function redirect($url, $delay = 0, $statusCode = 302)
  {
    $actionInstance = $this->context->getActionStack()->getLastEntry()->getActionInstance();
    $result = sfView::SUCCESS;
    if ($this->context->getUser()->hasFlash('error'))
    {
      $result = sfView::ERROR;
    }

    saExecutionFilter::notifyPostExecuteActionEvent($this, $this->dispatcher, $actionInstance, $result);

    parent::redirect($url, $delay, $statusCode);
  }
}
