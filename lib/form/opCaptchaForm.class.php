<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saCaptchaForm
 *
 * @package    SfAdvanced
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saCaptchaForm extends BaseForm
{
  public function configure()
  {
    $this->setWidget('captcha', new saWidgetFormCaptcha());
    $this->setValidator('captcha', new sfValidatorPass());

    $formatter = new sfWidgetFormSchemaFormatterList($this->widgetSchema);
    $formatter->setRowFormat("<li>%field%%help%\n%hidden_fields%</li>\n");
    $formatter->setHelpFormat('<div class="help">%help%</div>');

    $this->widgetSchema->addFormFormatter('saCaptchaFormFormatter', $formatter);
    $this->widgetSchema->setFormFormatterName('saCaptchaFormFormatter');

    $this->validatorSchema->setPostValidator(new sfValidatorCallback(array(
      'callback' => array($this, 'validateCaptchaString'),
    )));

    $this->widgetSchema->setHelp('captcha', 'Please input the below keyword.');
  }

  public function validateCaptchaString($validator, $value, $arguments)
  {
    $answer = '';
    if (isset($_SESSION['captcha_keystring']))
    {
      $answer = $_SESSION['captcha_keystring'];
      unset($_SESSION['captcha_keystring']);
    }

    if ($value['captcha'] !== $answer)
    {
      $error = new sfValidatorError($validator, 'invalid');

      throw new sfValidatorErrorSchema($validator, array('captcha' => $error));
    }

    return $value;
  }
}
