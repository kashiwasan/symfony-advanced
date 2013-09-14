<?php
include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new saTestFunctional(new saBrowser(), new lime_test(null, new lime_output_color()));
$browser
  ->info('Login')
  ->get('/')
  ->click('ログイン', array('admin_user' => array(
    'username' => 'admin',
    'password' => 'password',
  )))

// CSRF
  ->info('/default/login - CSRF')
  ->post('/default/login')
  ->checkCSRF()
;
