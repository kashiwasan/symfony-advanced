<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * MemberImageForm
 *
 * @package    SfAdvanced
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class MemberImageForm extends BaseForm
{
  public function configure()
  {
    $this->member = $this->getOption('member');
    $this->setWidget('file', new sfWidgetFormInputFile());
    $this->setValidator('file', new saValidatorImageFile());
    $this->widgetSchema->setNameFormat('member_image[%s]');
  }

  public function bindAndSave(array $taintedValues = null, array $taintedFiles = null)
  {
    $this->bind($taintedValues, $taintedFiles);
    if ($this->isValid())
    {
      return $this->save();
    }
    return false;
  }

  public function save()
  {
    $count = $this->member->getMemberImage()->count();
    if ($count >= 3)
    {
      throw new saRuntimeException('Cannot add an image any more.');
    }

    $file = new File();
    $file->setFromValidatedFile($this->getValue('file'));
    $file->setName('m_'.$this->member->getId().'_'.$file->getName());

    $memberImage = new MemberImage();
    $memberImage->setMember($this->member);
    $memberImage->setFile($file);
    if (!$count)
    {
      $memberImage->setIsPrimary(true);
    }
    return $memberImage->save();
  }
}
