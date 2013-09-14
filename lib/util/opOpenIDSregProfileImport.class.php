<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saOpenIDSregProfileImport
 *
 * @package    SfAdvanced
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saOpenIDSregProfileImport extends saProfileImport
{
  public $tableToSfAdvanced = array(
    'nickname' => 'name',
    'email'    => 'email',
    'fullname' => 'name',
    'dob'      => 'sa_preset_birthday',
    'gender'   => 'sa_preset_sex',
    'postcode' => 'sa_preset_postal_code',
    'country'  => 'sa_preset_country',
    'language' => 'language',
    'timezone' => 'time_zone',
  );

  public
    $profiles = array('dob', 'gender', 'postcode', 'country'),
    $names = array('nickname', 'fullname'),
    $configs = array('language', 'timezone'),
    $emails = array('email');

  public function setData($data)
  {
    $result = array();
    $list = array_flip($this->tableToSfAdvanced);

    foreach ($list as $k => $v)
    {
      $methodName = $this->getSetterMethodName($k);
      $result[$k] = $this->$methodName($data, $v);
    }

    return $result;
  }

  public function setOpPresetSex($data)
  {
    $sex = $this->getValue($data, 'gender');
    if ($sex)
    {
      $this->setMemberProfile($this->member, 'sa_preset_sex', ('M' === $sex ? 'Man' : 'Woman'));
    }
  }

  public function setLanguage($data)
  {
    $language = $this->getValue($data, 'language');
    if ($language)
    {
      $language = str_replace('-', '_', $language);
      $this->member->setConfig('language', $language);
    }
  }

  public function __call($name, $arguments)
  {
    if (0 === strpos($name, 'set'))
    {
      $data = $arguments[0];
      $key = $arguments[1];

      if (in_array($key, $this->profiles))
      {
        if (empty($data[$key]))
        {
          return null;
        }

        $this->setMemberProfile($this->member, $this->tableToSfAdvanced[$key], $data[$key]);
      }
      elseif (in_array($key, $this->names))
      {
        $nickname = $this->getValue($data, 'nickname');
        if (!$nickname)
        {
          $nickname = $this->getValue($data, 'fullname');
        }
        $this->member->setName($nickname);
      }
      elseif (in_array($key, $this->emails))
      {
        $email = $this->getValue($data, 'email');
        if (!$email)
        {
          return null;
        }

        if (saToolkit::isMobileEmailAddress($email))
        {
          $this->member->setConfig('mobile_address', $email);
        }
        else
        {
          $this->member->setConfig('pc_address', $email);
        }
      }
      elseif (in_array($key, $this->images))
      {
        return null;
      }
      elseif (in_array($key, $this->configs))
      {
        if (empty($data[$key]))
        {
          return null;
        }

        $this->member->setConfig($this->tableToSfAdvanced[$key], $data[$key]);
      }
      else
      {
        throw new BadMethodCallException(sprintf('Unknown method %s::%s', get_class($this), $name));
      }
    }
    else
    {
      throw new BadMethodCallException(sprintf('Unknown method %s::%s', get_class($this), $name));
    }
  }
}
