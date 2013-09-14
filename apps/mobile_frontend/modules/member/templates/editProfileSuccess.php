<?php sa_mobile_page_title(__('Edit profile')) ?>

<?php sa_include_form('profileForm', array($memberForm, $profileForm), array(
    'url'    => url_for('@member_editProfile'),
    'align'  => 'center',
    'button' => __('Save')
)) ?>
