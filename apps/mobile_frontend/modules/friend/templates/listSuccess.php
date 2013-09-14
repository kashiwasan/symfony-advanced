<?php sa_mobile_page_title(__('%Friend% list')) ?>

<center>
<?php sa_include_pager_total($pager); ?>
</center>

<?php
$list = array();
foreach ($pager->getResults() as $member) {
  $list[] = sa_link_to_member($member, array('link_target' => sprintf('%s(%d)', $member->getName(), $member->countFriends())));
}
$option = array(
  'border' => true,
);
op_include_list('friendList', $list, $option);
?>

<?php sa_include_pager_navigation($pager, '@friend_list?page=%d&id='.$id , array('is_total' => false)); ?><br>

<?php if ($relation->isSelf()): ?>
<?php slot('sa_mobile_footer_menu') ?>
<?php echo link_to(__('Manage %friend%'), 'friend/manage') ?>
<?php end_slot(); ?>
<?php endif; ?>
