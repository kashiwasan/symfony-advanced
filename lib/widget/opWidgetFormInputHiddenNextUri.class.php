<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saWidgetFormInputHiddenNextUri represents a hidden HTML input tag for next_uri
 *
 * @package    SfAdvanced
 * @subpackage widget
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saWidgetFormInputHiddenNextUri extends sfWidgetFormInputHidden
{
  protected function configure($sations = array(), $attributes = array())
  {
    parent::configure($sations, $attributes);

    $routing = sfContext::getInstance()->getRouting();
    $request = sfContext::getInstance()->getRequest();

    $value = $request->getGetParameter('next_uri', null);
    if (is_null($value))
    {
      $value = $routing->getCurrentInternalUri();
      if ($_SERVER['QUERY_STRING'])
      {
        if (false !== strpos($value, '?'))
        {
          $value .= '&';
        }
        else
        {
          $value .= '?';
        }

        $value .= $_SERVER['QUERY_STRING'];
      }
    }

    if ($request->isMethod(sfWebRequest::POST))
    {
      $value = 'default/csrfError';
    }

    $this->setAttribute('value', $value);
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $attributes = array_merge($attributes, $this->getAttributes());
    return parent::render($name, $value, $attributes, $errors);
  }
}
