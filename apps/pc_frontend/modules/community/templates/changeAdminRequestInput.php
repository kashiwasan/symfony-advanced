<?php slot('firstRow') ?>
<tr><th><?php echo __('Photo') ?></th><td><?php echo sa_link_to_member($member, array('link_target' => sa_image_tag_sf_image($member->getImageFileName(), array('size' => '76x76')))) ?> </td></tr>
<tr><th><?php echo __('%nickname%', array('%nickname%' => $sa_term['nickname']->titleize())) ?></th><td><?php echo sa_link_to_member($member) ?></td></tr>
<?php end_slot() ?>
<?php sa_include_form('communityAdminRequest', $form, array(
  'title' => __('Take over the administrator of "%1%"', array('%1%' => $community->getName())),
  'firstRow' => get_slot('firstRow')
)) ?>
