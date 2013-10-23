<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * SiteConfig form.
 *
 * @package    SfAdvanced
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class SiteConfigForm extends BaseForm
{
  public function configure()
  {
    $snsConfig = sfConfig::get('sfadvanced_sns_config');
    $category = sfConfig::get('sfadvanced_sns_category');
    if (empty($category[$this->getOption('category')]))
    {
      return false;
    }

    foreach ($category[$this->getOption('category')] as $configName)
    {
      if (empty($snsConfig[$configName]))
      {
        continue;
      }

      $this->setWidget($configName, saFormItemGenerator::generateWidget($snsConfig[$configName]));
      $this->setValidator($configName, saFormItemGenerator::generateValidator($snsConfig[$configName]));
      $this->widgetSchema->setLabel($configName, $snsConfig[$configName]['Caption']);
      if (isset($snsConfig[$configName]['Help']))
      {
        $this->widgetSchema->setHelp($configName, $snsConfig[$configName]['Help']);
      }

      $value = saConfig::get($configName);
      if ($value instanceof sfOutputEscaperArrayDecorator)
      {
        $value = $value->getRawValue();
      }

      $this->setDefault($configName, $value);
    }

    $this->widgetSchema->setNameFormat('sns_config[%s]');
  }

  public function save()
  {
    $config = sfConfig::get('sfadvanced_sns_config');
    foreach ($this->getValues() as $key => $value)
    {
      $snsConfig = Doctrine::getTable('SiteConfig')->retrieveByName($key);
      if (!$snsConfig)
      {
        $snsConfig = new SiteConfig();
        $snsConfig->setName($key);
      }
      $snsConfig->setValue($value);
      $snsConfig->save();
    }
  }
}
