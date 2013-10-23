<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(6, new lime_output_color());

$snsConfig1 = Doctrine::getTable('SiteConfig')->find(1);
$snsConfig2 = Doctrine::getTable('SiteConfig')->find(2);
$snsConfig3 = new SiteConfig();
$snsConfig3->setName('xxxxxxxxxx');

//------------------------------------------------------------
$t->diag('SiteConfig');
$t->diag('SiteConfig::getConfig()');
$t->isa_ok($snsConfig1->getConfig(), 'array');
$t->isa_ok($snsConfig2->getConfig(), 'array');
$t->ok(!$snsConfig3->getConfig());

//------------------------------------------------------------
$t->diag('SiteConfig::getValue()');
$t->is($snsConfig1->getValue(), 'test1');

//------------------------------------------------------------
$t->diag('SiteConfig::setValue()');
$snsConfig2->setValue(array('0', '1'));
$t->is($snsConfig2->getValue(), array('0', '1'));
$t->is($snsConfig2->rawGet('value'), serialize($snsConfig2->getValue()));
