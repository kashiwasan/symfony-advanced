<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saOpenIDAxProfileExport
 *
 * @package    SfAdvanced
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saOpenIDAxProfileExport extends saProfileExport
{
  public $tableToSfAdvanced = array(
    'http://schema.saenid.net/namePerson/friendly'     => 'name',
    'http://schema.saenid.net/contact/email'           => 'email',
    'http://schema.saenid.net/namePerson'              => 'name',
    'http://schema.saenid.net/birthDate'               => 'sa_preset_birthday',
    'http://schema.saenid.net/birthDate/birthYear'     => 'sa_preset_birthday',
    'http://schema.saenid.net/birthDate/birthMonth'    => 'sa_preset_birthday',
    'http://schema.saenid.net/birthDate/birthday'      => 'sa_preset_birthday',
    'http://schema.saenid.net/person/gender'           => 'sa_preset_sex',
    'http://schema.saenid.net/contact/postalCode/home' => 'sa_preset_postal_code',
    'http://schema.saenid.net/contact/phone/default'   => 'sa_preset_telephone_number',
    'http://schema.saenid.net/contact/country/home'    => 'sa_preset_country',
    'http://schema.saenid.net/media/biography'         => 'sa_preset_self_introduction',
    'http://schema.saenid.net/pref/language'           => 'language',
    'http://schema.saenid.net/pref/timezone'           => 'time_zone',
    'http://schema.saenid.net/media/image/default'     => 'image',
    'http://schema.saenid.net/media/image/aspect11'    => 'image',
    'http://schema.saenid.net/media/image/aspect43'    => 'image',
    'http://schema.saenid.net/media/image/aspect34'    => 'image',
  );

  public
    $profiles = array(
      'http://schema.saenid.net/birthDate',
      'http://schema.saenid.net/birthDate/birthYear',
      'http://schema.saenid.net/birthDate/birthMonth',
      'http://schema.saenid.net/birthDate/birthday',
      'http://schema.saenid.net/person/gender',
      'http://schema.saenid.net/contact/postalCode/home',
      'http://schema.saenid.net/contact/country/home',
      'http://schema.saenid.net/media/biography',
      'http://schema.saenid.net/contact/phone/default',
    ),
    $names = array(
      'http://schema.saenid.net/namePerson/friendly',
      'http://schema.saenid.net/namePerson',
    ),
    $emails = array(
      'http://schema.saenid.net/contact/email',
    ),
    $images = array(
      'http://schema.saenid.net/media/image/default',
      'http://schema.saenid.net/media/image/aspect11',
      'http://schema.saenid.net/media/image/aspect43',
      'http://schema.saenid.net/media/image/aspect34',
    ),
    $configs = array(
      'http://schema.saenid.net/pref/language',
      'http://schema.saenid.net/pref/timezone',
    );

  public function getPersonGender()
  {
    $sex = (string)$this->member->getProfile('sa_preset_sex');
    if (!$sex)
    {
      return '';
    }

    return ('Man' === $sex ? 'M' : 'W');
  }

  public function getPrefLanguage()
  {
    $language = $this->member->getConfig('language');

    return str_replace('_', '-', $language);
  }

  public function getBirthDateBirthYear()
  {
    $birth = (string)$this->member->getProfile('sa_preset_birthday');
    if (!$birth)
    {
      return '';
    }

    $tmp = explode('-', $birth);

    return $tmp[0];
  }

  public function getBirthDateBirthMonth()
  {
    $birth = (string)$this->member->getProfile('sa_preset_birthday');
    if (!$birth)
    {
      return '';
    }

    $tmp = explode('-', $birth);

    return $tmp[1];
  }

  public function getBirthDateBirthday()
  {
    $birth = (string)$this->member->getProfile('sa_preset_birthday');
    if (!$birth)
    {
      return '';
    }

    $tmp = explode('-', $birth);

    return $tmp[2];
  }

  public function getMediaImageAspect11()
  {
    return $this->getMemberImageURI(array('size' => '180x180'));
  }

  public function getMediaImageAspect43()
  {
    return $this->getMemberImageURI(array('size' => '640x480'));
  }

  public function getMediaImageAspect34()
  {
    return $this->getMemberImageURI(array('size' => '480x640'));
  }

  protected function getGetterMethodName($key)
  {
    $path = str_replace('/', '_', substr(parse_url($key, PHP_URL_PATH), 1));

    return 'get'.sfInflector::camelize($path);
  }
}
