<?php

$executeLoader = false;
include dirname(__FILE__).'/../../bootstrap/functional.php';
include dirname(__FILE__).'/../../bootstrap/database.php';

$numOfTests = 15;
$tester = new saTestFunctional(
  new saBrowser(),
  new lime_test($numOfTests, new lime_output_color())
);

$t = $tester->test();

Doctrine_Core::getTable('SnsConfig')->set('enable_jsonapi', true);
$apiKeyMember1 = Doctrine_Core::getTable('Member')->find(1)->getApiKey();

if (in_array('saTimelinePlugin', ProjectConfiguration::getActive()->getPlugins()))
{
  // saTimelinePlugin breaks standard JSON APIs (activity/*.json)
  $tester->test()->fail('unable to run tests if saTimelinePlugin is installed');
  return;
}

$tester
  ->info('/activity/post.json - single image upload')
  ->postWithFiles(
    '/activity/post.json',
    array('apiKey' => $apiKeyMember1, 'body' => 'hogehoge'),
    array('images[0]' => dirname(__FILE__).'/uploads/dot.gif')
  )
  ->with('response')->isStatusCode(200);

$response = json_decode($tester->getResponse()->getContent(), true);
$t->is($response['status'], 'success');

$activityImage = Doctrine_Core::getTable('ActivityImage')->findByActivityDataId($response['data']['id']);
$t->is(count($activityImage), 1);
$t->is(count($response['data']['images']), 1);
$t->is($response['data']['images'][0]['small_uri'], 'http://localhost/cache/img/gif/w48_h48/'.$activityImage[0]->File->name.'.gif');
$t->is($response['data']['images'][0]['full_uri'], 'http://localhost/cache/img/gif/w_h/'.$activityImage[0]->File->name.'.gif');

$tester
  ->info('/activity/post.json - invalid image')
  ->postWithFiles(
    '/activity/post.json',
    array('apiKey' => $apiKeyMember1, 'body' => 'hogehoge'),
    array('images[0]' => dirname(__FILE__).'/uploads/plaintext.txt')
  )
  ->with('response')->isStatusCode(400);

$tester
  ->info('/activity/post.json - multiple image upload')
  ->postWithFiles(
    '/activity/post.json',
    array('apiKey' => $apiKeyMember1, 'body' => 'hogehoge'),
    array('images[0]' => dirname(__FILE__).'/uploads/dot.gif', 'images[1]' => dirname(__FILE__).'/uploads/white.jpg')
  )
  ->with('response')->isStatusCode(200);

$response = json_decode($tester->getResponse()->getContent(), true);
$t->is($response['status'], 'success');

$activityImage = Doctrine_Core::getTable('ActivityImage')->findByActivityDataId($response['data']['id']);
$t->is(count($activityImage), 2);
$t->is(count($response['data']['images']), 2);
$t->is($response['data']['images'][0]['small_uri'], 'http://localhost/cache/img/gif/w48_h48/'.$activityImage[0]->File->name.'.gif');
$t->is($response['data']['images'][0]['full_uri'], 'http://localhost/cache/img/gif/w_h/'.$activityImage[0]->File->name.'.gif');
$t->is($response['data']['images'][1]['small_uri'], 'http://localhost/cache/img/jpg/w48_h48/'.$activityImage[1]->File->name.'.jpg');
$t->is($response['data']['images'][1]['full_uri'], 'http://localhost/cache/img/jpg/w_h/'.$activityImage[1]->File->name.'.jpg');
