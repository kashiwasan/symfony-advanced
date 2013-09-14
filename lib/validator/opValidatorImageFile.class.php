<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saValidatorImageFile validates a date
 *
 * @package    SfAdvanced
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saValidatorImageFile extends sfValidatorFile
{
  protected function configure($sations = array(), $messages = array())
  {
    parent::configure($sations, $messages);
    $this->setOption('mime_types', 'web_images');

    $maxFilesize = saConfig::get('image_max_filesize');
    switch (strtoupper(substr($maxFilesize, -1)))
    {
      case 'K' :
        $maxFilesize = (int)$maxFilesize * 1024;
        break;
      case 'M' :
        $maxFilesize = (int)$maxFilesize * 1024 * 1024;
        break;
    }
    
    $this->setOption('max_size', (int)$maxFilesize);
  }

  protected function doClean($value)
  {
    try
    {
      return parent::doClean($value);
    }
    catch (sfValidatorError $e)
    {
      if ($e->getCode() == 'max_size')
      {
        $arguments = $e->getArguments(true);
        throw new sfValidatorError($this, 'max_size', array('max_size' => saConfig::get('image_max_filesize'), 'size' => $arguments['size']));
      }
      throw $e;
    }
  }
}
