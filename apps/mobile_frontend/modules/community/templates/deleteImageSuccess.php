<?php sa_mobile_page_title(__('Settings'), __('Delete Photo')) ?>
<?php echo __('Do you delete this photo?') ?>
<hr color="<?php echo $sa_color["core_color_12"] ?>">
<center>
<?php echo sa_image_tag_sf_image($community->getImageFileName(), array('size' => '120x120', 'format' => 'jpg')) ?><br>
<?php echo sprintf('[%s]',link_to(__('Expansion'), sf_image_path($community->getImageFileName(), array('size' => saConfig::get('mobile_image_max_size'), 'format' => 'jpg')))) ?><br>
</center>
<hr color="<?php echo $sa_color["core_color_12"] ?>">
<?php sa_include_form('deleteForm', $form, array(
  'url'    => url_for('@community_deleteImage?id='.$id),
  'button' => __('Delete'),
  'align'  => 'center'
)) ?>
