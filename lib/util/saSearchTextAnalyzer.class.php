<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saSearchTextAnalyzer
 *
 * @package    SfAdvanced
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saSearchTextAnalyzer extends Doctrine_Search_Analyzer_Standard implements Doctrine_Search_Analyzer_Interface
{
  public function analyze($text)
  {
    $result = parent::analyze($text);

    saApplicationConfiguration::registerZend();

    Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8());
    $analyzer = Zend_Search_Lucene_Analysis_Analyzer::getDefault();
    $analyzer->setInput($text, 'UTF-8');

    while (($nextToken = $analyzer->nextToken()) !== null)
    {
      $result[] = $nextToken->getTermText();
    }

    return $result;
  }
}
