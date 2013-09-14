<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saGlobalPartialView
 *
 * @package    SfAdvanced
 * @subpackage view
 * @author     ShogoKawahara <kawahara@tejimaya.net>
 */
class saGlobalPartialView extends sfPartialView
{
 /**
  * Configure template for this view
  *
  */
  public function configure()
  {
    $this->setDecorator(false);
    $this->setTemplate($this->actionName.$this->getExtension());
    $this->setDirectory($this->context->getConfiguration()->getGlobalTemplateDir($this->getTemplate()));
  }
}
