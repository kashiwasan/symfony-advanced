<?php sa_mobile_page_title(__('Invite friends for %1%', array('%1%' => $sa_config['sns_name']))) ?>

<?php sa_include_form('inviteForm', $form, array(
  'url'    => url_for('member/invite'),
  'button' => __('Send'),
  'align'  => 'center',
)) ?>
