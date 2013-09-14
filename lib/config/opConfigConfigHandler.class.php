<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saConfigConfigHandler
 *
 * @package    SfAdvanced
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saConfigConfigHandler extends sfYamlConfigHandler
{
  public function execute($configFiles)
  {
    // get our prefix
    $prefix = strtolower($this->getParameterHolder()->get('prefix', 'sfadvanced_'));

    $config = $this->parseYamls($configFiles);

    $data = "array(\n";
    $categoryList = "array(\n";
    $categoryAttributeList = "array(\n";

    foreach ($config as $category => $keys)
    {
      $categoryList .= sprintf("'%s' => array(\n", $category);
      $categoryAttributeList .= '';

      if ($keys)
      {
        foreach ($keys as $key => $value)
        {
          if ($key === '_attributes')
          {
            $categoryAttributeList .= sprintf("'%s' => %s,\n", $category, var_export($value, true));
          }
          else
          {
            $categoryList .= sprintf("'%s',\n", $key);
            $data .= sprintf("'%s' => %s,\n", $key, var_export($value, true));
          }
        }
      }

      $categoryList .= "),\n";
    }

    $data .= "),\n";
    $categoryList .= "),\n";
    $categoryAttributeList .= "),\n";

    $format = "<?php\n"
            . "sfConfig::add(array('%s' => %s));\n"
            . "sfConfig::add(array('%s' => %s));\n"
            . "sfConfig::add(array('%s' => %s));";

    return sprintf($format, $prefix.'config', $data, $prefix.'category', $categoryList, $prefix.'category_attribute', $categoryAttributeList);
  }
}
