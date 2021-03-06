<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saNotificationMailTemplateLoaderConfigSample
 *
 * @package    SfAdvanced
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
class saNotificationMailTemplateLoaderConfigSample extends sfTemplateAbstractSwitchableLoader
{
  public function doLoad($template, $renderer = 'twig')
  {
    if ($sample = Doctrine::getTable('NotificationMail')->fetchTemplateFromConfigSample($template))
    {
      if ($sample[1])
      {
        return new sfTemplateStorageString($sample[1], $renderer);
      }
    }

    return false;
  }
}
