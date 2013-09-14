<?php sa_mobile_page_title($member->getName(), __('Add %my_friend%')); ?>

<?php sa_include_form('linkForm', $form, array(
  'url'    => url_for('friend/link?id='.$id),
  'button' => __('Submit'),
  'align'  => 'center'
)) ?>
