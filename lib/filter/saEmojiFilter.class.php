<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saEmojiFilter converts Emoji symbols in the response text
 *
 * Emoji is the picture characters or emoticons used in Japan.
 *
 * @package    SfAdvanced
 * @subpackage filter
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saEmojiFilter extends sfFilter
{
 /**
  * Executes this filter.
  *
  * @param sfFilterChain $filterChain A sfFilterChain instance
  */
  public function execute($filterChain)
  {
    $filterChain->execute();

    $response = $this->getContext()->getResponse();
    $request = $this->getContext()->getRequest();
    $content = $response->getContent();

    if (!$request->isMobile())
    {
      list($list, $content) = saToolkit::replacePatternsToMarker($content);
    }

    $content = SfAdvanced_KtaiEmoji::convertEmoji($content);

    if (!$request->isMobile())
    {
      $content = str_replace(array_keys($list), array_values($list), $content);
    }

    $response->setContent($content);
  }
}
