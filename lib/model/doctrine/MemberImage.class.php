<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class MemberImage extends BaseMemberImage
{
  public function getIsPrimary()
  {
    if ($this->rawGet('is_primary'))
    {
      return true;
    }

    $primaryImage = $this->Member->MemberImage;
    if ($primaryImage)
    {
      return (bool)($primaryImage->id == $this->id);
    }

    return false;
  }

  public function postDelete($event)
  {
    $this->File->delete();
  }
}
