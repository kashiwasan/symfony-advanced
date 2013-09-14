<?php sa_mobile_page_title($member->getName(), __('Photo')) ?>
<center>
<?php $images = $member->getMemberImage() ?>
<?php for ($i = 0; $i < 3 && $i < count($images); $i++) : ?>
<?php $image = $images[$i] ?>
<?php echo sa_image_tag_sf_image($image->getFile(), array('size' => '120x120', 'format' => 'jpg')) ?><br>
<?php echo '['.link_to(__('Expansion'), sf_image_path($image->getFile(), array('size' => saConfig::get('mobile_image_max_size'), 'format' => 'jpg'))).']' ?><br><br>
<?php endfor; ?>
</center>
<?php slot('sa_mobile_footer_menu') ?>
<?php echo sa_link_to_member($member, array('link_target' => __("%1%'s Profile", array('%1%' => $member->getName())))); ?>
<?php end_slot(); ?>
