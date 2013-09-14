<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

abstract class saBaseSendMailTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', null),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
    ));
  }

  public function initialize(sfEventDispatcher $dispatcher, sfFormatter $formatter)
  {
    parent::initialize($dispatcher, $formatter);

    // This is just a hack to make SfAdvanced generate a valid url in some helper function
    $_SERVER['SCRIPT_NAME'] = '/index.php';
  }

  protected function execute($arguments = array(), $sations = array())
  {
    $this->saenDatabaseConnection();

    // saMailSend requires Zend
    saApplicationConfiguration::registerZend();

    saDoctrineRecord::setDefaultCulture(sfConfig::get('default_culture', 'ja_JP'));
  }

  protected function saenDatabaseConnection()
  {
    new sfDatabaseManager($this->configuration);
  }

  protected function getContextByEmailAddress($address)
  {
    $application = 'pc_frontend';
    if (saToolkit::isMobileEmailAddress($address))
    {
      $application = 'mobile_frontend';
    }

    if (!sfContext::hasInstance($application))
    {
      $context = sfContext::createInstance($this->createConfiguration($application, 'prod'), $application);
    }
    else
    {
      $context = sfContext::getInstance($application);
    }

    return $context;
  }
}
