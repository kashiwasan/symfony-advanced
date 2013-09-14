<?php echo __('CSRF attack detected.'); ?>

<?php use_helper('Javascript') ?>
<?php sa_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>
