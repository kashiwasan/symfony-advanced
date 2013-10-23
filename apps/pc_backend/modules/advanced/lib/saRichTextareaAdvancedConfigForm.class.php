<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saRichTextareaSfAdvancedConfigForm
 *
 * @package    SfAdvanced
 * @subpackage form 
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class saRichTextareaSfAdvancedConfigForm extends sfForm
{
  static protected $defaultModeChoice = array(
    'text'    => 'Text Mode',
    'preview' => 'Preview Mode'
  );

  public function configure()
  {
    $this->setWidget('default_mode', new sfWidgetFormChoice(array('choices' => 
      array_map(array(sfContext::getInstance()->getI18n(), '__') , self::$defaultModeChoice),
      'expanded' => true
    )));
    $this->setValidator('default_mode', new sfValidatorChoice(array('choices' => array_keys(self::$defaultModeChoice))));
    $this->setDefault('default_mode', Doctrine::getTable('SiteConfig')->get('richtextarea_default_mode', 'text'));
    $this->widgetSchema->setLabel('default_mode', 'Default edit mode');
    $this->widgetSchema->setNameFormat('config[%s]');
  }

  public function save()
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    foreach ($this->getValues() as $key => $value)
    {
      Doctrine::getTable('SiteConfig')->set('richtextarea_'.$key, $value);
    }
  }
}

