<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saPearRest interacts with a PEAR channel for SfAdvanced plugin
 *
 * @package    SfAdvanced
 * @subpackage plugin
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saPearRest extends sfPearRest
{
  public function retrieveData($url, $accept = false, $forcestring = false, $channel = false)
  {
    $content = parent::retrieveData($url, $accept, true, $channel);
    $result = $this->parseXML($content);
    return $result;
  }

  public function retrieveXml($url)
  {
    $content = $this->downloadHttp($url);
    $result = @saToolkit::loadXmlString($content, array(
      'return' => 'SimpleXMLElement',
    ));

    return $result;
  }

  public function saveCache($url, $contents, $lastmodified, $nochange = false, $cacheid = null)
  {
    if (!is_array($contents))
    {
      $contents = $this->parseXML($contents);
    }
    return parent::saveCache($url, $contents, $lastmodified, $nochange, $cacheid);
  }

  public function parseXML($content)
  {
    if (is_array($content))
    {
      return $content;
    }

    $parser = new PEAR_XMLParser();
    $parser->parse($content);
    return $parser->getData();
  }
}
