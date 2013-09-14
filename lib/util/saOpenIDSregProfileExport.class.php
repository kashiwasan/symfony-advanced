<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saOpenIDSregProfileExport
 *
 * @package    SfAdvanced
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saOpenIDSregProfileExport extends saProfileExport
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

  public function getGender()
  {
    $sex = (string)$this->member->getProfile('sa_preset_sex');
    if (!$sex)
    {
      return '';
    }

    return ('Man' === $sex ? 'M' : 'W');
  }

  public function getLanguage()
  {
    $language = $this->member->getConfig('language');

    return substr($language, 0, strpos($language, '_'));
  }
}
