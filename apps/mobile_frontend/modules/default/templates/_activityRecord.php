<?php use_helper('opActivity') ?>

<?php echo sa_link_to_member($activity->getMember()) ?>
&nbsp;<?php echo sa_activity_body_filter($activity) ?>
<font color="<?php echo $sa_color['core_color_19'] ?>">[<?php echo sa_format_activity_time(strtotime($activity->getCreatedAt())) ?>]</font>
<?php if ($activity->getPublicFlag() != ActivityDataTable::PUBLIC_FLAG_SNS): ?>
<font color="<?php echo $sa_color['core_color_19'] ?>">[<?php echo $activity->getPublicFlagCaption() ?>]</font>
<?php endif; ?>
<?php if (!isset($isOperation) || $isOperation): ?>
<div align="right">
<?php if ($activity->getMemberId() == $sf_user->getMemberId()): ?>
<?php echo link_to(__('Delete'), 'member/deleteActivity?id='.$activity->getId()) ?>
<?php endif; ?>
</div>
<?php endif; ?>
