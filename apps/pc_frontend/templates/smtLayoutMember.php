<?php use_helper('Javascript') ?>
<?php use_helper('saAsset') ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>
<?php sa_smt_use_stylesheet('bootstrap') ?>
<?php sa_smt_use_stylesheet('smt_main') ?>
<?php sa_smt_include_stylesheets() ?>
<meta name="viewport" content="width=320px,user-scalable=no" />
<?php if (saConfig::get('enable_jsonapi') && saToolkit::isSecurePage()): ?>
<?php
$jsonData = array(
  'apiKey' => $sf_user->getMemberApiKey(),
  'apiBase' => app_url_for('api', 'homepage'),
  'baseUrl' => $sf_request->getRelativeUrlRoot().'/',
);

echo javascript_tag('
var sfadvanced = '.json_encode($jsonData).';
');
?>
<?php endif ?>
<?php sa_smt_use_javascript('jquery.min.js') ?>
<?php sa_smt_use_javascript('jquery.tmpl.min.js') ?>
<?php sa_smt_use_javascript('smt_main') ?>
<?php sa_smt_use_javascript('smt_notify') ?>
<?php sa_smt_use_javascript('smt_tosaka') ?>
<?php sa_smt_use_javascript('smt_menu') ?>
<?php sa_smt_include_javascripts() ?>
</head>
<body id="<?php printf('page_%s_%s', $this->getModuleName(), $this->getActionName()) ?>" class="<?php echo saToolkit::isSecurePage() ? 'secure_page' : 'insecure_page' ?>">
<?php include_partial('global/tosaka') ?>
<div id="face" class="row">
  <?php if (isset($sa_layout['member'])): ?>
  <div class="span2">
    <?php echo sa_image_tag_sf_image($sa_layout['member']->getImageFileName(), array('size' => '48x48')) ?>
  </div>
  <div class="span8">
    <div class="row face-name"><?php echo $sa_layout['member']->getName() ?></div>
    <div class="row screen-name">
      <?php $screenName = $sa_layout['member']->getConfig('sa_screen_name') ?>
      <?php if ($screenName): ?>
      <a href="#">@<?php echo $screenName ?></a>
      <?php endif ?>
    </div>
  </div>
  <div class="span2 center"><?php echo link_to(sa_image_tag('HomeIcon.png', array('height' => '48')), '@homepage') ?></div>
  <?php endif ?>
</div>

<?php if ($sf_user->hasFlash('error')): ?>
<div id="global-error" class="row">
  <div class="alert alert-error">
    <?php echo __($sf_user->getFlash('error')); ?>
  </div>
</div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('notice')): ?>
<div id="global-error" class="row">
  <div class="alert alert-info">
    <?php echo __($sf_user->getFlash('notice')); ?>
  </div>
</div>
<?php endif; ?>

<?php echo $sf_content ?>

<div id="smartphoneFooter">
<?php include_component('default', 'smartphoneFooterGadgets') ?>
</div>

</body>
</html>
