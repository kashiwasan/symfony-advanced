<?php
$sations->setDefault('button', __('Send'));
$sations->setDefault('url', $sf_request->getCurrentUri());
$sations->setDefault('method','post');
$sations->setDefault('mark_required_field', true);
?>

<?php if ($sations['form'] instanceof saAuthRegisterForm): ?>
<?php echo $sations['form']->renderFormTag($sations['url'], array('method' => $sations['method'])) ?>
<?php $forms = $sations['form']->getAllForms() ?>
<?php else: ?>
<form action="<?php echo $sations['url'] ?>" method="<?php echo $sations['method'] ?>">
<?php $forms = ($sations['form'] instanceof sfForm) ? array($sations['form']): $sations['form'] ?>
<?php endif; ?>

<?php include_customizes($id, 'formTop') ?>

<?php $hasRequiredField = false ?>

<?php slot('form') ?>
<?php foreach ($forms as $form): ?>
<?php echo $form->renderHiddenFields() ?>
<?php
foreach ($form as $name => $field)
{
  if ($field->isHidden()) continue;
  $attributes = array();
  $widget = $field->getWidget();
  $validator = $form->getValidator($name);

  if ($widget instanceof sfWidgetFormInputPassword)
  {
    $widget = saToolkit::appendMobileInputModeAttributesForFormWidget($widget, 'alphabet');
  }

  if ($widget instanceof saWidgetFormProfile)
  {
    $widget = $widget->getOption('widget');
    $validator = $validator->getOption('validator');
  }

  if ($widget instanceof sfWidgetFormChoice)
  {
    if ($widget->getRenderer() instanceof sfWidgetFormSelectRadio || $widget->getRenderer() instanceof sfWidgetFormSelectCheckbox)
    {
      $widget->setOption('renderer_options', 
        array_merge(array(
          'formatter' => array('saWidgetFormSelectFormatterMobile', 'formatter'),
          'separator' => "<br>\n"
        ), $widget->getOption('renderer_options'))
      );
    }
  }
  elseif ($widget instanceof sfWidgetFormSelectRadio || $widget instanceof sfWidgetFormSelectCheckbox)
  {
    $widget->setOption('formatter', array('saWidgetFormSelectFormatterMobile', 'formatter'));
    $widget->setOption('separator', "<br>\n");
  }

  if ($sations['mark_required_field'] 
    && !($validator instanceof sfValidatorPass)
    && !($validator instanceof sfValidatorSchema)
    && $validator->getOption('required'))
  {
    echo sprintf('<font color="%s">*</font>', saColorConfig::get('core_color_22'));
    $hasRequiredField = true;
  }

  echo $field->renderRow($attributes);
}
?>
<?php endforeach; ?>
<?php end_slot(); ?>

<?php if ($hasRequiredField): ?>
<?php echo __('%0% is required field.', array('%0%' => sprintf('<font color="%s">*</font>', saColorConfig::get('core_color_22')))) ?>
<hr color="<?php echo saColorConfig::get('core_color_11') ?>">
<?php endif; ?>

<?php slot('form_global_error') ?>
<?php foreach ($forms as $form): ?>
<?php if ($form->hasGlobalErrors()): ?>
<?php echo $form->renderGlobalErrors() ?>
<?php endif; ?>
<?php endforeach; ?>
<?php end_slot(); ?>
<?php if (get_slot('form_global_error')): ?>
<?php echo get_slot('form_global_error') ?><br><br>
<?php endif; ?>

<?php include_slot('form') ?>

<?php if (!empty($sations['align'])): ?>
<div align="<?php echo $sations['align'] ?>">
<?php else: ?>
<div>
<?php endif; ?>
<input type="submit" value="<?php echo $sations['button'] ?>">
</div>
<?php include_customizes($id, 'formBottom') ?>
</form>
