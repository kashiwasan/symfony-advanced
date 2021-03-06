<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

require_once dirname(__FILE__).'/../../../lib/config/saApplicationConfiguration.class.php';

class pc_frontendConfiguration extends saApplicationConfiguration
{
  public function configure()
  {
    sfConfig::set('sa_is_use_captcha', true);
  }

  public function initialize()
  {
    parent::initialize();

    sfWidgetFormSchema::setDefaultFormFormatterName('pc');
  }
}
