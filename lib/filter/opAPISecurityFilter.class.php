<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saAPISecurityFilter
 *
 * @package    SfAdvanced
 * @subpackage filter
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saAPISecurityFilter extends sfBasicSecurityFilter
{
  public function execute($filterChain)
  {
    // the user is not authenticated
    if (!$this->context->getUser()->isAuthenticated())
    {
      // HTTP 401 Unauthorized
      $this->sendError(401);
    }

    // the user doesn't have access
    $credential = $this->getUserCredential();
    if (!is_null($credential) && !$this->context->getUser()->hasCredential($credential))
    {
      // HTTP 403 Forbidden
      $this->sendError(403);
    }

    // the user has access, continue
    $filterChain->execute();
  }

  protected function sendError($statusCode)
  {
    $response = $this->getContext()->getResponse();
    $response->setStatusCode($statusCode);

    $response->sendHttpHeaders();

    echo $response->getStatusCode().' '.$response->getStatusText();

    throw new sfStopException();
  }
}
