<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saJsonApiAction
 *
 * @package    SfAdvanced
 * @subpackage action
 * @author     Kimura Youichi <kim.upsilon@gmail.com>
 */
class saJsonApiActions extends sfActions
{
  protected
    $member = null,
    $doEscapeText = false;

  public function execute($request)
  {
    $this->forward404Unless(saConfig::get('enable_jsonapi'));

    $moduleName = strtolower($this->moduleName);
    sfConfig::set('mod_'.$moduleName.'_view_class', 'saJsonApi');
    $this->getResponse()->setContentType('application/json');

    $enableEscaping = true;
    if (isset($request['escaping']))
    {
      $enableEscaping = in_array($request['escaping'], array('1', 'on', 'true'));
    }
    sfConfig::set('sf_escaping_strategy', $enableEscaping);

    return parent::execute($request);
  }

  public function renderJSON(array $data)
  {
    $json = json_encode($data);

    return $this->renderText($json);
  }

  public function forward400($message = null)
  {
    $exception = new saErrorHttpException($message);
    throw $exception->setHttpStatusCode(400);
  }

  public function forward400If($condition, $message = null)
  {
    if ($condition)
    {
      $this->forward400($message);
    }
  }

  public function forward400Unless($condition, $message = null)
  {
    if (!$condition)
    {
      $this->forward400($message);
    }
  }

  public function forward401($message = null)
  {
    $exception = new saErrorHttpException($message);
    throw $exception->setHttpStatusCode(401);
  }

  public function forward401If($condition, $message = null)
  {
    if ($condition)
    {
      $this->forward401($message);
    }
  }

  public function forward401Unless($condition, $message = null)
  {
    if (!$condition)
    {
      $this->forward401($message);
    }
  }

  public function forward403($message = null)
  {
    $exception = new saErrorHttpException($message);
    throw $exception->setHttpStatusCode(403);
  }

  public function forward403If($condition, $message = null)
  {
    if ($condition)
    {
      $this->forward403($message);
    }
  }

  public function forward403Unless($condition, $message = null)
  {
    if (!$condition)
    {
      $this->forward403($message);
    }
  }
}
