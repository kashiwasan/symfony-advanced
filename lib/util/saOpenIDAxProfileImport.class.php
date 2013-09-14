<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saOpenIDAxProfileImport
 *
 * @package    SfAdvanced
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saOpenIDAxProfileImport extends saProfileImport
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

  public function getValue($data, $name, $ns = 'http://schema.saenid.net/')
  {
    if (empty($data[$ns.$name]))
    {
      return null;
    }

    return $data[$ns.$name];
  }

  public function setOpPresetBirthday($data)
  {
    $birthday = $this->getValue($data, 'birthDate');
    $y = $this->getValue($data, 'birthDate/birthYear');
    $m = $this->getValue($data, 'birthDate/birthMonth');
    $d = $this->getValue($data, 'birthDate/birthday');

    if ($birthday)
    {
      $this->setMemberProfile($this->member, 'sa_preset_birthday', array_shift($birthday));
    }
    elseif ($y && $m && $d)
    {
      $date = array_shift($y).'-'.array_shift($m).'-'.array_shift($d);
      $this->setMemberProfile($this->member, 'sa_preset_birthday', $date);
    }
  }

  public function setOpPresetSex($data)
  {
    $sex = $this->getValue($data, 'person/gender');
    if ($sex)
    {
      $sex = array_shift($sex);

      $this->setMemberProfile($this->member, 'sa_preset_sex', ('M' === $sex ? 'Man' : 'Woman'));
    }
  }

  public function setLanguage($data)
  {
    $language = $this->getValue($data, 'pref/language');
    if ($language)
    {
      $language = str_replace('-', '_', array_shift($language));
      $this->member->setConfig('language', $language);
    }
  }

  public function setImage($data)
  {
    $form = new MemberImageForm(array(), array('member' => $this->member));
    $imageUri = '';

    $pathList = array(
      'media/image/default', 'media/image/aspect11',
      'media/image/aspect43', 'media/image/aspect34',
    );

    foreach ($pathList as $v)
    {
      $img = $this->getValue($data, $v);
      if ($img)
      {
        $imageUri = $img;
        break;
      }
    }

    if ($imageUri)
    {
      $client = new Zend_Http_Client(array_shift($imageUri));
      $response = $client->request();
      if (!$response->isError())
      {
        $type = $response->getHeader('Content-type');
        if (is_array($type))
        {
          $type = array_shift($type);
        }

        $tmppath = tempnam(sys_get_temp_dir(), 'IMG');

        $fh = fopen($tmppath, 'w');
        fwrite($fh, $response->getBody());
        fclose($fh);

        $image = array(
          'tmp_name' => $tmppath,
          'type'     => $type,
        );

        $validator = new saValidatorImageFile();
        $validFile = $validator->clean($image);

        $file = new File();
        $file->setFromValidatedFile($validFile);
        $file->setName('m_'.$this->member->getId().'_'.$file->getName());

        $memberImage = new MemberImage();
        $memberImage->setMember($this->member);
        $memberImage->setFile($file);
        $memberImage->setIsPrimary(true);

        $memberImage->save();
      }
    }
  }
}
