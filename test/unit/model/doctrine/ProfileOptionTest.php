<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(2, new lime_output_color());

//------------------------------------------------------------
$t->diag('saProfileOptionEmulator');
$sation = new saProfileOptionEmulator();
$sation->id = 1;
$sation->value = 'test';
$t->is($sation->id, 1);
$t->is($sation->value, 'test');
