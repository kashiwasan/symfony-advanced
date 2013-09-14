<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision46_AddColumnsToActivityData extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('activity_data', 'template', 'string', '64', array(
      'comment' => 'Template name',
    ));
    $this->addColumn('activity_data', 'template_param', 'array', '', array(
      'comment' => 'Params for template',
    ));
  }

  public function down()
  {
    $this->removeColumn('activity_data', 'template');
    $this->removeColumn('activity_data', 'template_param');
  }
}

