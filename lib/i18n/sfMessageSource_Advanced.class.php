<?php

class sfMessageSource_SfAdvanced extends sfMessageSource_XLIFF
{
  public function &loadData($filename)
  {
    $result = parent::loadData($filename);

    // SfAdvanced doesn't allow translating empty string
    unset($result['']);

    return $result;
  }
}
