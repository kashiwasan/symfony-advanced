<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saFormItemGenerator generates form items (widgets and validators)
 *
 * @package    SfAdvanced
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saFormItemGenerator
{
  protected static $choicesType = array('checkbox', 'select', 'radio');

 /**
  * This method exists only for BC
  */
  public static function arrayKeyCamelize(array $array)
  {
    foreach ($array as $key => $value)
    {
      unset($array[$key]);
      $array[sfInflector::classify($key)] = $value;
    }

    return $array;
  }

  public static function generateWidgetParams($field, $choices = array())
  {
    $params = array();

    if ($field['Caption'])
    {
      $params['label'] = $field['Caption'];
    }

    if (in_array($field['FormType'], self::$choicesType))
    {
      $params['choices'] = array_map(array(sfContext::getInstance()->getI18N(), '__'), $choices);
      if (!empty($field['Choices']) && is_array($field['Choices']))
      {
        $params['choices'] = array_map(array(sfContext::getInstance()->getI18N(), '__'), $field['Choices']);
      }
    }

    if (!empty($field['Default']))
    {
      $params['default'] = $field['Default'];
    }

    if (!empty($field['Params']))
    {
      $params = array_merge($params, $field['Params']);
    }

    return $params;
  }

  public static function generateWidget($field, $choices = array())
  {
    $field = self::arrayKeyCamelize($field);
    $params = self::generateWidgetParams($field, $choices);

    if (in_array($field['FormType'], self::$choicesType))
    {
      if ('select' === $field['FormType'])
      {
        if (!$field['IsRequired'])
        {
          $params['choices'] = array('' => sfContext::getInstance()->getI18N()->__('Please Select')) + $params['choices'];
        }
      }
      else
      {
        $params['expanded'] = true;
      }
    }


    switch ($field['FormType'])
    {
      case 'checkbox':
        $params['multiple'] = true;
        $obj = new sfWidgetFormChoice($params);
        break;
      case 'select':
        $obj = new sfWidgetFormChoice($params);
        break;
      case 'radio':
        $obj = new sfWidgetFormChoice($params);
        break;
      case 'textarea':
        $obj = new sfWidgetFormTextarea($params);
        break;
      case 'rich_textarea':
        $obj = new saWidgetFormRichTextarea($params);
        break;
      case 'password':
        $obj = new sfWidgetFormInputPassword($params);
        break;
      case 'date':
        unset($params['choices']);
        $params['culture'] = sfContext::getInstance()->getUser()->getCulture();
        $params['month_format'] = 'number';
        if (!$field['IsRequired'])
        {
          $params['can_be_empty'] = true;
        }
        $obj = new saWidgetFormDate($params);
        break;
      case 'increased_input':
        $obj = new saWidgetFormInputIncreased($params);
        break;
      case 'language_select':
        $languages = sfConfig::get('sa_supported_languages');
        $choices = saToolkit::getCultureChoices($languages);
        $obj = new sfWidgetFormChoice(array('choices' => $choices));
        break;
      case 'country_select':
        $info = sfCultureInfo::getInstance(sfContext::getInstance()->getUser()->getCulture());
        $obj = new sfWidgetFormChoice(array('choices' => $info->getCountries()));
        break;
      case 'region_select':
        $list = include(sfContext::getInstance()->getConfigCache()->checkConfig('config/regions.yml'));
        $type = $field['ValueType'];
        if ('string' !== $type && isset($list[$type]))
        {
          $list = $list[$type];
          $list = array_combine($list, $list);
        }
        else
        {
          foreach ($list as $k => $v)
          {
            if ($v)
            {
              $list[$k] = array_combine($v, $v);
            }
          }
        }
        $list = saToolkit::arrayMapRecursive(array(sfContext::getInstance()->getI18N(), '__'), $list);
        $obj = new sfWidgetFormChoice(array('choices' => $list));
        break;
      case 'image_size':
        foreach (sfImageHandler::getAllowedSize() as $v)
        {
          $params['choices'][$v] = sfContext::getInstance()->getI18N()->__($v);
        }
        $params['choices'] = $params['choices'] + array('' => sfContext::getInstance()->getI18N()->__('Full Size'));
        $obj = new sfWidgetFormChoice($params);
        break;
      default:
        $obj = new sfWidgetFormInput($params);
        break;
    }

    return $obj;
  }

  public static function generateValidator($field, $choices = array())
  {
    $field = self::arrayKeyCamelize($field);
    $sation = array('required' => $field['IsRequired'], 'trim' => $field['IsTrim']);

    if (!$choices && !empty($field['Choices']))
    {
      $choices = array_keys($field['Choices']);
    }

    if ('checkbox' === $field['FormType'])
    {
      $sation['choices'] = $choices;
      $sation['multiple'] = true;
      $obj = new sfValidatorChoice($sation);

      return $obj;
    }
    if ('select' === $field['FormType'] || 'radio' === $field['FormType'])
    {
      $sation = array('choices' => $choices);
      $sation['required'] = $field['IsRequired'];
      $obj = new sfValidatorChoice($sation);

      return $obj;
    }

    if ('integer' === $field['ValueType'])
    {
      if (isset($field['ValueMin']) && is_numeric($field['ValueMin']))
      {
        $sation['min'] = $field['ValueMin'];
      }
      if (isset($field['ValueMax']) && is_numeric($field['ValueMax']))
      {
        $sation['max'] = $field['ValueMax'];
        if (isset($sation['min']) && (int)$sation['min'] > (int)$sation['max'])
        {
          unset($sation['min']);
          unset($sation['max']);
        }
      }
    }
    elseif ('date' === $field['FormType'])
    {
      if (isset($field['ValueMin']) && false !== strtotime($field['ValueMin']))
      {
        $sation['min'] = $field['ValueMin'];
      }
      if (isset($field['ValueMax']) && false !== strtotime($field['ValueMax']))
      {
        $sation['max'] = $field['ValueMax'];
        if (isset($sation['min']) && strtotime($sation['min']) > strtotime($sation['max']))
        {
          unset($sation['min']);
          unset($sation['max']);
        }
      }
    }
    else
    {
      if (isset($field['ValueMin']))
      {
        $sation['min_length'] = $field['ValueMin'];
      }
      if (isset($field['ValueMax']))
      {
        $sation['max_length'] = $field['ValueMax'];

        if (1 > (int)$field['ValueMax'] || (isset($field['ValueMin']) && (int)$field['ValueMin'] > (int)$field['ValueMax']))
        {
          unset($sation['min_length']);
          unset($sation['max_length']);
        }
      }
    }

    if ('date' === $field['FormType'])
    {
      $sation['date_format_range_error'] = 'Y-m-d';
      $obj = new saValidatorDate($sation);

      return $obj;
    }

    switch ($field['ValueType'])
    {
      case 'email':
        $obj = new sfValidatorEmail($sation);
        break;
      case 'pc_email':
        $obj = new saValidatorPCEmail($sation);
        break;
      case 'mobile_email':
        $obj = new sfValidatorMobileEmail($sation);
        break;
      case 'integer':
        $obj = new sfValidatorInteger($sation);
        break;
      case 'regexp':
        $sation['pattern'] = $field['ValueRegexp'];
        $obj = new sfValidatorRegex($sation);
        break;
      case 'url':
        $obj = new sfValidatorUrl($sation);
        break;
      case 'password':
        $obj = new sfValidatorPassword($sation);
        break;
      case 'image_size':
        $obj = new saValidatorImageSize($sation);
        break;
      case 'pass':
        $obj = new sfValidatorPass($sation);
        break;
      default:
        $obj = new saValidatorString($sation);
        break;
    }

    return $obj;
  }

  public static function generateSearchWidget($field, $choices = array())
  {
    $field = self::arrayKeyCamelize($field);
    $params = self::generateWidgetParams($field, $choices);

    switch ($field['FormType'])
    {
      // selection
      case 'checkbox':
      case 'select':
      case 'radio':
        $obj = new sfWidgetFormChoice($params);
        break;
      // doesn't allow searching
      case 'increased_input':
      case 'language_select':
      case 'password':
        $obj = null;
        break;
      // country
      case 'country_select':
        $info = sfCultureInfo::getInstance(sfContext::getInstance()->getUser()->getCulture());
        $params['choices'] = array('' => '') + $info->getCountries();
        $obj = new sfWidgetFormChoice($params);
        break;
      // region
      case 'region_select':
        $list = (array)include(sfContext::getInstance()->getConfigCache()->checkConfig('config/regions.yml'));
        $type = $field['ValueType'];
        if ('string' !== $type && isset($list[$type]))
        {
          $list = $list[$type];
          $list = array_combine($list, $list);
        }
        else
        {
          foreach ($list as $k => $v)
          {
            if ($v)
            {
              $list[$k] = array_combine($v, $v);
            }
          }
        }
        $list = saToolkit::arrayMapRecursive(array(sfContext::getInstance()->getI18N(), '__'), $list);
        $params['choices'] = array('' => '')+ $list;
        $obj = new sfWidgetFormChoice($params);
        break;
      // date
      case 'date':
        unset($params['choices']);
        $params['culture'] = sfContext::getInstance()->getUser()->getCulture();
        $params['month_format'] = 'number';
        $params['can_be_empty'] = true;
        $obj = new saWidgetFormDate($params);
        break;
      // text and something else
      default:
        $obj = new sfWidgetFormInput($params);
        break;
    }

    return $obj;
  }

  public static function filterSearchQuery($q, $column, $value, $field)
  {
    $field = self::arrayKeyCamelize($field);

    if (!$q)
    {
      $q = new Doctrine_Query();
    }

    if (empty($value))
    {
      return $q;
    }

    switch ($field['FormType'])
    {
      // selection
      case 'checkbox':
      case 'select':
      case 'radio':
        $q->andWhere($column.' = ?', $value);
        break;
      case 'date':
        $q->andWhere($column.' LIKE ?', $value);
        break;
      // doesn't allow searching
      case 'increased_input':
      case 'language_select':
      case 'password':
        break;
      case 'country_select':
      case 'region_select':
        $q->andWhere($column.' = ?', $value);
        break;
      // text and something else
      default:
        $q->andWhereLike($column, $value);
        break;
    }

    return $q;
  }
}
