<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opNotificationCenter
 *
 * @package    SfAdvanced
 * @subpackage util
 * @author     Shouta Kashiwagi <kashiwagi@tejimaya.com>
 */
class opSmartphoneLayoutUtil
{
  protected static $parameters;

  static public function setLayoutParameters($parameters)
  {
    self::$parameters = $parameters;
    sfContext::getInstance()->getEventDispatcher()->connect('template.filter_parameters', array(__CLASS__, 'filterTemplateParameters'));
  }

  static public function filterTemplateParameters(sfEvent $event, $parameters)
  {
    if (isset($parameters['sf_type']) && 'layout' === $parameters['sf_type'])
    {
      $parameters['sa_layout'] = self::$parameters;
    }

    return $parameters;
  }
}
