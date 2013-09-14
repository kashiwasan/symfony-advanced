<?php sa_include_form('registerForm', $form, array(
  'url'    => url_for('member/registerInput?token='.$token).'?guid=on',
  'button' => __('Register'),
  'align'  => 'center' 
)) ?>
