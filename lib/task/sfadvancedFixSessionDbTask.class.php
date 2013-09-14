<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * sfadvancedFixSessionDbTask
 *
 * @package    SfAdvanced
 * @subpackage task
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class sfadvancedFixSessionDbTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', true),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));

    $this->namespace        = 'sfadvanced';
    $this->name             = 'fix-session-db';
    $this->briefDescription = 'Fix serious bug in managing session with your database';
    $this->detailedDescription = <<<EOF
The [sfadvanced:fix-session-db|INFO] task fixes serious bug in managing session with your database.
Call it with:

  [./symfony sfadvanced:fix-session-db|INFO]
EOF;
  }

  protected function execute($arguments = array(), $sations = array())
  {
    $this->logSection('fix-session-db', 'Begin to fix session table structure');

    $this->saenDatabaseConnection();

    $this->logSection('fix-session-db', 'Now changing definition of your session table structure');

    $conn = saDoctrineQuery::getMasterConnectionDirect();
    $conn->export->alterTable('session', array(
      'change' => array(
        'id' => array(
          'definition' => array(
            'type'    => 'string',
            'length'  => 128,
            'primary' => '1',
          ),
        ),
      ),
    ));

    $this->logSection('fix-session-db', 'Clear current session data');
    $conn->execute('TRUNCATE session');

    $this->logSection('fix-session-db', 'Finish to fix session table structure');
  }

  protected function saenDatabaseConnection()
  {
    new sfDatabaseManager($this->configuration);
  }
}
