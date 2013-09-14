<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opWidgetFormRichTextareaSfAdvancedExtension
 *
 * @package    SfAdvanced
 * @subpackage widget
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
abstract class opWidgetFormRichTextareaSfAdvancedExtension
{
  static public function getPlugins()
  {
    return array();
  }

  static public function getButtons()
  {
    return array();
  }

  static public function getButtonOnClickActions()
  {
    return array();
  }

  static public function getConvertCallbacks()
  {
    return array();
  }

  static public function getHtmlConverts()
  {
    return array();
  }

  static public function configure(&$configs)
  {
  }
}
