<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * MemberProfile form.
 *
 * @package    SfAdvanced
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class MemberProfileForm extends BaseForm
{
  public function __construct($profileMember = array(), $sations = array(), $CSRFSecret = null)
  {
    parent::__construct(array(), $sations, $CSRFSecret);

    foreach ($profileMember as $profile)
    {
      if (!$profile)
      {
        continue;
      }

      $this->setDefault($profile->getName(), array(
        'value' => $profile->getValue(),
        'public_flag' => $profile->getPublicFlag()
      ));
    }
  }

  public function configure()
  {
    $this->widgetSchema->setNameFormat('profile[%s]');
  }

  public function save($memberId)
  {
    $values = $this->getValues();

    foreach ($values as $key => $value)
    {
      $profile = Doctrine::getTable('Profile')->retrieveByName($key);
      if (!$profile)
      {
        continue;
      }

      $memberProfile = Doctrine::getTable('MemberProfile')->retrieveByMemberIdAndProfileId($memberId, $profile->getId());

      if (is_null($value['value']))
      {
        if ($memberProfile)
        {
          if ($profile->isMultipleSelect())
          {
            $memberProfile->clearChildren();
          }
          $memberProfile->delete();
        }
        continue;
      }
      if (!$memberProfile)
      {
        $memberProfile = new MemberProfile();
        $memberProfile->setMemberId($memberId);
        $memberProfile->setProfileId($profile->getId());
      }

      $memberProfile->setPublicFlag($memberProfile->getProfile()->getDefaultPublicFlag());
      if (isset($value['public_flag']))
      {
        $memberProfile->setPublicFlag($value['public_flag']);
      }
      $memberProfile->save();

      if ($profile->isMultipleSelect())
      {
        $ids = array();
        $_values = array();
        if ('date' === $profile->getFormType())
        {
          $_values = array_map('intval', explode('-', $value['value']));
          $sations = $profile->getProfileOption();
          foreach ($sations as $sation)
          {
            $ids[] = $sation->getId();
          }
          $memberProfile->setValue($value['value']);
        }
        else
        {
          $ids = $value['value'];
        }
        Doctrine::getTable('MemberProfile')->createChild($memberProfile, $memberId, $profile->getId(), $ids, $_values);
      }
      else
      {
        $memberProfile->setValue($value['value']);
      }

      $memberProfile->save();
    }

    return true;
  }

  public function setRegisterWidgets()
  {
    $profiles = Doctrine::getTable('Profile')->retrieveByIsDispRegist();
    $this->setProfileWidgets($profiles);
  }

  public function setConfigWidgets()
  {
    $profiles = Doctrine::getTable('Profile')->retrieveByIsDispConfig();
    $this->setProfileWidgets($profiles);
  }

  public function setSearchWidgets()
  {
    $profiles = Doctrine::getTable('Profile')->retrieveByIsDispSearch();
    $this->setProfileWidgets($profiles);
  }

  public function setAllWidgets()
  {
    $profiles = Doctrine::getTable('Profile')->retrievesAll();
    $this->setProfileWidgets($profiles);
  }

  protected function setProfileWidgets($profiles)
  {
    $presetList = saToolkit::getPresetProfileList();

    foreach ($profiles as $profile)
    {
      if ('mobile_frontend' === sfConfig::get('sf_app'))
      {
        if ('sa_preset_country' === $profile->getName() || 'sa_preset_region' === $profile->getName())
        {
          continue;
        }
      }

      $profileI18n = $profile->Translation[sfContext::getInstance()->getUser()->getCulture()]->toArray();
      $profileWithI18n = $profile->toArray() + $profileI18n;

      $widgetOptions = array(
        'widget' => saFormItemGenerator::generateWidget($profileWithI18n, $this->getFormOptionsValue($profile->getId())),
      );
      $validatorOptions = array(
        'validator' => saFormItemGenerator::generateValidator($profileWithI18n, $this->getFormOptions($profile->getId())),
      );

      if ($profile->getIsEditPublicFlag())
      {
        $widgetOptions['is_edit_public_flag'] = $validatorOptions['is_edit_public_flag'] = true;
        if (!$this->getDefault($profile->getName()))
        {
          $this->setDefault($profile->getName(), array('public_flag' => $profile->getDefaultPublicFlag()));
        }
      }

      $this->widgetSchema[$profile->getName()] = new saWidgetFormProfile($widgetOptions);
      $this->validatorSchema[$profile->getName()] = new saValidatorProfile($validatorOptions);

      $this->widgetSchema[$profile->getName()]->profile = $profile;
      $this->validatorSchema[$profile->getName()]->profile = $profile;

      $this->widgetSchema->setHelp($profile->getName(), $profileWithI18n['info']);
      if ($profile->isPreset())
      {
        $this->widgetSchema->setLabel($profile->getName(), $presetList[$profile->getRawPresetName()]['Caption']);
        if ('sa_preset_birthday' === $profile->getName())
        {
          $this->widgetSchema->setHelp($profile->getName(), 'The public_flag for your age can be configure at "Settings" page.');
        }
      }
    }
  }

  private function getFormOptions($profileId)
  {
    $result = array();
    $sations = Doctrine::getTable('ProfileOption')->retrieveByProfileId($profileId);

    foreach ($sations as $sation)
    {
      $result[] = $sation->getId();
    }

    return $result;
  }

  private function getFormOptionsValue($profileId)
  {
    $result = array();
    $sations = Doctrine::getTable('ProfileOption')->retrieveByProfileId($profileId);

    foreach ($sations as $sation)
    {
      $result[$sation->getId()] = $sation->getValue();
    }

    return $result;
  }

  private function updateDefaultsFromObject($obj)
  {
    $this->setDefaults(array_merge($this->getDefaults(), $obj->toArray()));
  }
}
