<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opSymfonyDefaultRouteCollection
 *
 * @package    SfAdvanced
 * @subpackage routing
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opSymfonyDefaultRouteCollection extends sfRouteCollection
{
  public function __construct(array $options)
  {
    parent::__construct($options);

    $this->routes['default_symfony'] = new sfRoute(
      '/symfony/:action/*',
      array('module' => 'default')
    );

    $this->routes['default_index'] = new sfRoute(
      '/:module',
      array('action' => 'index')
    );

    $this->routes['default'] = new opDeprecatedRoute(
      '/:module/:action/*'
    );
  }
}
