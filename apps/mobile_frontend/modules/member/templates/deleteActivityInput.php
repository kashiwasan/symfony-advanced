<?php slot('activity') ?>
<?php echo __('Do you delete this %activity%?') ?>
<hr color="<?php echo $sa_color['core_color_11'] ?>">
<?php include_partial('default/activityRecord', array('activity' => $activity, 'isOperation' => false)) ?>
<hr color="<?php echo $sa_color['core_color_11'] ?>">
<?php end_slot() ?>

<?php sa_include_parts('yesNo', 'delete_activity', array(
  'body' => get_slot('activity'),
  'yes_form' => new BaseForm(),
  'no_method' => 'get',
  'no_url' => url_for('friend/showActivity'),
  'align' => 'center',
)) ?>
