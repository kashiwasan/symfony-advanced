<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class sfadvancedGenerateMigrationsTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'sfadvanced';
    $this->name             = 'generate-migrations';

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', null),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));

    $this->briefDescription = 'generates migration scripts automatically';
    $this->detailedDescription = <<<EOF
The [./symfony sfadvanced:generate-migrations|INFO] task generates migration scripts automatically.

Call it with:
  [./symfony sfadvanced:generate-migrations|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    throw new Exception('The "sfadvanced:generate-migrations" is not supported since OpenPNE 3.5.2. Use "doctrine:generate-migrations-diff".');
  }
}
