<?php sa_mobile_page_title(__("SNS Member's %activity%", array(
  '%activity%' => $sa_term['activity']->pluralize()->titleize(),
))) ?>
<?php if ($pager->getNbResults() || isset($form)): ?>
<?php if ($pager->getNbResults()): ?>
<center>
<?php sa_include_pager_total($pager) ?>
</center>
<?php endif; ?>
<?php $params = array(
  'title' => '',
  'activities' => $pager->getResults(),
) ?>
<?php if (isset($form)): ?>
<?php $params['form'] = $form; ?>
<?php endif; ?>
<?php include_partial('default/activityBox', $params) ?>
<?php if ($pager->getNbResults()): ?>
<?php sa_include_pager_navigation($pager, 'member/showAllMemberActivity?page=%d') ?>
<?php endif; ?>
<?php else: ?>
<?php sa_include_parts('box', 'ActivityBox', array(
  'body' => __('There is no %activity%.'),
  'title' => ''
)) ?>
<?php endif; ?>
