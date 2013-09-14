<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saCheckEnabledApplicationFilter
 *
 * @package    SfAdvanced
 * @subpackage filter
 * @author     Kousuke Ebihara
 */
class saCheckEnabledApplicationFilter extends sfFilter
{
  /**
   * Executes this filter.
   *
   * @param sfFilterChain $filterChain A sfFilterChain instance
   */
  public function execute($filterChain)
  {
      $current = $this->context->getRouting()->getCurrentRouteName();
      $configName = 'enable_'.$this->getParameter('app', 'pc');

      if (!saConfig::get($configName))
      {
        if ($current !== 'error')
        {
          $this->context->getController()->redirect('@error');
          throw new sfStopException();
        }
      }

      $filterChain->execute();
  }
}
