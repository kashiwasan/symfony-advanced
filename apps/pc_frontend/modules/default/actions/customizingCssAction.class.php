<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * customizingCss action.
 *
 * @package    SfAdvanced
 * @subpackage default
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class customizingCssAction extends sfAction
{
  public function execute($request)
  {
    $css = Doctrine::getTable('SiteConfig')->get('customizing_css', '');
    $this->getResponse()->setContent($css);
    $this->getResponse()->setContentType('text/css');

    // cache
    $filesystem = new sfFilesystem();
    $dir = sfConfig::get('sf_web_dir').DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'css';
    @$filesystem->mkdirs($dir);
    $filesystem->chmod($dir, 0777);
    $cssPath = $dir.DIRECTORY_SEPARATOR.'customizing.css';
    file_put_contents($cssPath, $css);
    $filesystem->chmod($cssPath, 0666);

    $this->getResponse()->setHttpHeader('Last-Modified', sfWebResponse::getDate(time()));

    return sfView::NONE;
  }
}
