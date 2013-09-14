<?php sa_mobile_page_title($community->getName(), __('Take over administrator of this community')); ?>

<font color="<?php echo $sa_color['core_color_19'] ?>"><?php echo __('%nickname%', array('%nickname%' => $sa_term['nickname']->titleize())) ?>:</font><br>
<?php echo $member->getName() ?>
<br><br>
<?php sa_include_form('communityAdminRequest', $form, array(
  'button' => __('Submit'),
  'align'  => 'center'
)) ?>
