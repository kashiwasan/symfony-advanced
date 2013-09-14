<?php

require_once sfConfig::get('sf_symfony_lib_dir').'/helper/AssetHelper.php';

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saAssetHelper provides helper function for Assets like css or javascript
 * this helpler refered to symfony's AssetHelper
 *
 * @package    SfAdvanced
 * @subpackage helper
 * @author     Yuya Watanabe <watanabe@sfadvanced.jp>
 */


/**
 * Returns <script> tags for all javascripts for smartphone pages added to the response object.
 *
 * you can use this helper to decide the location of javascripts in pages.
 * by default, if you don't call this helper, sfadvanced will automatically include javascripts before </head>.
 * calling this helper disables this behavior.
 *
 * @return string <script> tags
 *
 * @see get_javascripts()
 */
function sa_smt_get_javascripts()
{
  $response = sfContext::getInstance()->getResponse();
  sfConfig::set('symfony.asset.javascripts_included', true);

  $html = '';
  foreach ($response->getSmtJavascripts() as $file => $sations)
  {
    $html .= javascript_include_tag($file, $sations);
  }

  return $html;
}

/**
 * Prints <script> tags for all javascripts for smartphone pages added to the response object.
 *
 * @see get_javascripts()
 * @see sa_smt_get_javascripts()
 */
function sa_smt_include_javascripts()
{
  echo sa_smt_get_javascripts();
}

/**
 * Returns <link> tags for all stylesheets smartphone pages added to the response object.
 *
 * You can use this helper to decide the location of stylesheets in pages.
 * By default, if you don't call this helper, sfadvanced will automatically include stylesheets before </head>.
 * Calling this helper disables this behavior.
 *
 * @return string <link> tags
 *
 * @see get_stylesheets()
 */
function sa_smt_get_stylesheets()
{
  $response = sfContext::getInstance()->getResponse();
  sfConfig::set('symfony.asset.stylesheets_included', true);

  $html = '';
  foreach ($response->getSmtStylesheets() as $file => $sations)
  {
    $html .= stylesheet_tag($file, $sations);
  }

  return $html;
}

/**
 * Prints <link> tags for all stylesheets for smartphone pages added to the response object.
 *
 * @see get_stylesheets()
 * @see sa_smt_get_stylesheets()
 */
function sa_smt_include_stylesheets()
{
  echo sa_smt_get_stylesheets();
}


/**
 * Adds a stylesheet for smartphone pages to the response object.
 *
 * @see saWebResponse->addSmtStylesheet()
 */
function sa_smt_use_stylesheet($css, $position = '', $sations = array())
{
  sfContext::getInstance()->getResponse()->addSmtStylesheet($css, $position, $sations);
}

/**
 * Adds a javascript for smartphone pages to the response object.
 *
 * @see saWebResponse->addSmtJavascript()
 */
function sa_smt_use_javascript($js, $position = '', $sations = array())
{
  sfContext::getInstance()->getResponse()->addSmtJavascript($js, $position, $sations);
}
