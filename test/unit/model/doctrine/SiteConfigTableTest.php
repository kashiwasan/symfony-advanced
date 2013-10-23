<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(5, new lime_output_color());

$table = Doctrine::getTable('SiteConfig');

//------------------------------------------------------------
$t->diag('SiteConfigTable');
$t->diag('SiteConfigTable::retrieveByName()');
$snsConfig = $table->retrieveByName('sns_name');
$t->isa_ok($snsConfig, 'SiteConfig');

//------------------------------------------------------------
$t->diag('SiteConfigTable::get()');
$t->is($table->get('sns_name'), 'test1');
$t->is($table->get('sns_name', 'xxx'), 'test1');
$t->is($table->get('xxx', 'xxx'), 'xxx');

//------------------------------------------------------------
$t->diag('SiteConfigTable::set()');
$table->set('foo', 'bar');
$t->is($table->get('foo'), 'bar');
