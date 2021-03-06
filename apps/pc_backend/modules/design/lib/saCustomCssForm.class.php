<?php

/**
 * this file is part of the sfadvanced package.
 * (c) sfadvanced project (http://www.sfadvanced.jp/)
 *
 * for the full copyright and license information, please view the license
 * file and the notice file that were distributed with this source code.
 */

class saCustomCssForm extends sfForm
{
  public function configure()
  {
    $this->setWidget('css', new sfWidgetFormTextarea(array(), array('rows' => '20', 'cols' => '70')));
    $this->setValidator('css', new saValidatorString(array('required' => false)));

    $this->setDefault('css', Doctrine::getTable('SiteConfig')->get('customizing_css'));

    $this->widgetSchema->setNameFormat('css[%s]');
  }

  public function save()
  {
    Doctrine::getTable('SiteConfig')->set('customizing_css', $this->getValue('css'));

    $filesystem = new sfFilesystem();
    $cssPath = sfConfig::get('sf_web_dir').DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR
             . 'css'.DIRECTORY_SEPARATOR.'customizing.css';
    if (is_file($cssPath))
    {
      @$filesystem->remove($cssPath);
    }
  }
}
