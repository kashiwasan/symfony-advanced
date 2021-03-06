<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(9, new lime_output_color());

$table = Doctrine::getTable('SkinConfig');

//------------------------------------------------------------
$t->diag('SiteConfigTable');
$t->diag('->reretrieveByPluginAndName()');
$skinConfig = $table->retrieveByPluginAndName('saSkinDummyPlugin', 'color_1');
$t->isa_ok($skinConfig, 'SkinConfig', '->reretrieveByPluginAndName() returns an instance of the SkinConfig');

//------------------------------------------------------------
$t->diag('->get()');
$t->is($table->get('saSkinDummyPlugin', 'color_1'), '#ffffff', '->get() returns value that has already settled');
$t->is($table->get('saSkinDummyPlugin', 'color_1', '#000000'), '#ffffff', '->get() returns value that has already settled even if a default value is specified');
$t->is($table->get('saSkinDummyPlugin', 'undefined_color'), null, '->get() returns null if the specified name is undefined');
$t->is($table->get('saSkinDummyPlugin', 'undefined_color', '#cccccc'), '#cccccc', '->get() returns default value if the specified name is undefined and a default value is specified');
$t->is($table->get('saUndefinedPlugin', 'color_1'), null, '->get() returns null if the specified plugin is undefined');
$t->is($table->get('saUndefinedPlugin', 'color_1', '#dddddd'), '#dddddd', '->get() returns default value if the specified plugin is undefined and a default value is specified');

//------------------------------------------------------------
$t->diag('->set()');
$table->set('saSkinDummyPlugin', 'foo_color', '#eeeeee');
$t->is($table->get('saSkinDummyPlugin', 'foo_color'), '#eeeeee', '->set() saves the specified value correctlly');
$table->set('saSkinDummyPlugin', 'color', '#999999');
$t->is($table->get('saSkinDummyPlugin', 'color'), '#999999', '->set() updates the specified configuration correctlly');
