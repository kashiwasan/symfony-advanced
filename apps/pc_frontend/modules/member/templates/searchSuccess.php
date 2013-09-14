<?php
$options = array(
  'title'  => __('Search Members'),
  'url'    => url_for('@member_search'),
  'button' => __('Search'),
  'method' => 'get'
);

op_include_form('searchMember', $filters, $options);
?>

<?php if ($pager->getNbResults()): ?>
<?php
$list = array();
foreach ($pager->getResults() as $key => $member)
{
  $list[$key] = array();
  $list[$key][__('%nickname%', array('%nickname%' => $sa_term['nickname']->titleize()))] = $member->getName();
  $introduction = $member->getProfile('sa_preset_self_introduction', true);
  if ($introduction)
  {
    $list[$key][__('Self Introduction')] = $introduction;
  }
  $list[$key][__('Last Login')] = sa_format_last_login_time($member->getLastLoginTime());
}

$options = array(
  'title'          =>  __('Search Results'),
  'pager'          => $pager,
  'link_to_page'   => '@member_search?page=%d',
  'link_to_detail' => '@member_profile?id=%d',
  'list'           => $list,
  'use_op_link_to_member' => true,
);

op_include_parts('searchResultList', 'searchCommunityResult', $options);
?>
<?php else: ?>
<?php sa_include_box('searchMemberResult', __('Your search queries did not match any members.'), array('title' => __('Search Results'))) ?>
<?php endif; ?>
