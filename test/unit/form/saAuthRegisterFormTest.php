<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------

class saDummyWebRequest extends sfAdvancedWebRequest
{
  public function getMobileUID()
  {
    return 'dummy';
  }
}

class saAuthDummyRegisterForm extends saAuthRegisterForm
{
}

//------------------------------------------------------------

$configuration = ProjectConfiguration::getApplicationConfiguration('mobile_frontend', 'test', true);
$context = sfContext::createInstance($configuration);

$oldRequest = $context->getRequest();

$request = new saDummyWebRequest($context->getEventDispatcher(), $oldRequest->getParameterHolder()->getAll(), $oldRequest->getAttributeHolder()->getAll(), $oldRequest->getOptions());
$context->set('request', $request);

$form = new saAuthDummyRegisterForm();

$params = array();
if (isset($form['_csrf_token']))
{
  $params['_csrf_token'] = $form->getCSRFToken();
}
$form->bind($params);

$t->is($form->getValue('mobile_uid'), 'dummy', 'Mobile UID can be fetched in registration.');
