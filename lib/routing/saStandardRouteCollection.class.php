<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saStandardRouteCollection
 *
 * @package    SfAdvanced
 * @subpackage routing
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saStandardRouteCollection extends sfDoctrineRouteCollection
{
  public function __construct(array $sations)
  {
    if (!empty($sations['is_acl']))
    {
      $sations['route_class'] = 'saDynamicAclRoute';
    }

    parent::__construct($sations);
  }

  protected function getRouteForCollection($action, $methods)
  {
    return new $this->routeClass(
      sprintf('%s/%s', $this->sations['prefix_path'], $action),
      array('module' => $this->sations['module'], 'action' => $action, 'sf_format' => 'html'),
      array_merge($this->sations['requirements'], array('sf_method' => $methods)),
      array('model' => $this->sations['model'], 'type' => 'list', 'method' => $this->sations['model_methods']['list'], 'privilege' => $this->getPrivilege($action))
    );
  }

  protected function getRouteForObject($action, $methods)
  {
    return new $this->routeClass(
      sprintf('%s/:%s/%s', $this->sations['prefix_path'], $this->sations['column'], $action),
      array('module' => $this->sations['module'], 'action' => $action, 'sf_format' => 'html'),
      array_merge($this->sations['requirements'], array('sf_method' => $methods)),
      array('model' => $this->sations['model'], 'type' => 'object', 'method' => $this->sations['model_methods']['object'], 'privilege' => $this->getPrivilege($action))
    );
  }

  protected function getRouteForList()
  {
    return new $this->routeClass(
      sprintf('%s', $this->sations['prefix_path']),
      array('module' => $this->sations['module'], 'action' => $this->getActionMethod('list'), 'sf_format' => 'html'),
      array_merge($this->sations['requirements'], array('sf_method' => 'get')),
      array('model' => $this->sations['model'], 'type' => 'list', 'method' => $this->sations['model_methods']['list'], 'privilege' => $this->getPrivilege('list'))
    );
  }

  protected function getRouteForNew()
  {
    return new $this->routeClass(
      sprintf('%s/%s', $this->sations['prefix_path'], $this->sations['segment_names']['new']),
      array('module' => $this->sations['module'], 'action' => $this->getActionMethod('new'), 'sf_format' => 'html'),
      array_merge($this->sations['requirements'], array('sf_method' => 'get')),
      array('model' => $this->sations['model'], 'type' => 'object', 'privilege' => $this->getPrivilege('create'))
    );
  }

  protected function getRouteForCreate()
  {
    return new $this->routeClass(
      sprintf('%s', $this->sations['prefix_path']),
      array('module' => $this->sations['module'], 'action' => $this->getActionMethod('create'), 'sf_format' => 'html'),
      array_merge($this->sations['requirements'], array('sf_method' => 'post')),
      array('model' => $this->sations['model'], 'type' => 'object', 'privilege' => $this->getPrivilege('create'))
    );
  }

  protected function getRouteForShow()
  {
    return new $this->routeClass(
      sprintf('%s/:%s', $this->sations['prefix_path'], $this->sations['column']),
      array('module' => $this->sations['module'], 'action' => $this->getActionMethod('show'), 'sf_format' => 'html'),
      array_merge($this->sations['requirements'], array('sf_method' => 'get')),
      array('model' => $this->sations['model'], 'type' => 'object', 'method' => $this->sations['model_methods']['object'], 'privilege' => $this->getPrivilege('show'))
    );
  }

  protected function getRouteForEdit()
  {
    return new $this->routeClass(
      sprintf('%s/:%s/%s', $this->sations['prefix_path'], $this->sations['column'], $this->sations['segment_names']['edit']),
      array('module' => $this->sations['module'], 'action' => $this->getActionMethod('edit'), 'sf_format' => 'html'),
      array_merge($this->sations['requirements'], array('sf_method' => 'get')),
      array('model' => $this->sations['model'], 'type' => 'object', 'method' => $this->sations['model_methods']['object'], 'privilege' => $this->getPrivilege('edit'))
    );
  }

  protected function getRouteForUpdate()
  {
    return new $this->routeClass(
      sprintf('%s/:%s', $this->sations['prefix_path'], $this->sations['column']),
      array('module' => $this->sations['module'], 'action' => $this->getActionMethod('update'), 'sf_format' => 'html'),
      array_merge($this->sations['requirements'], array('sf_method' => array('put', 'post'))),
      array('model' => $this->sations['model'], 'type' => 'object', 'method' => $this->sations['model_methods']['object'], 'privilege' => $this->getPrivilege('edit'))
    );
  }

  protected function getRouteForDelete()
  {
    return new $this->routeClass(
      sprintf('%s/:%s/delete', $this->sations['prefix_path'], $this->sations['column']),
      array('module' => $this->sations['module'], 'action' => $this->getActionMethod('delete'), 'sf_format' => 'html'),
      array('sf_method' => array('post', 'delete')),
      array('model' => $this->sations['model'], 'type' => 'object', 'method' => $this->sations['model_methods']['object'], 'privilege' => $this->getPrivilege('delete'))
    );
  }

  protected function getRouteForDeleteConfirm()
  {
    return new $this->routeClass(
      sprintf('%s/:%s/%s', $this->sations['prefix_path'], $this->sations['column'], 'delete'),
      array('module' => $this->sations['module'], 'action' => $this->getActionMethod('deleteConfirm'), 'sf_format' => 'html'),
      array('sf_method' => array('get')),
      array('model' => $this->sations['model'], 'type' => 'object', 'method' => $this->sations['model_methods']['object'], 'privilege' => $this->getPrivilege('delete'))
    );
  }

  protected function getPrivilege($action)
  {
    $privileges = array(
      'list'           => 'view',
      'show'           => 'view',
      'new'            => 'create',
      'create'         => 'create',
      'edit'           => 'edit',
      'update'         => 'edit',
      'delete'         => 'delete',
      'deleteConfirm'  => 'delete',
    );

    if (isset($this->sations['privileges']))
    {
      $privileges = array_merge($privileges, (array)$this->sations['privileges']);
    }

    if (isset($privileges[$action]))
    {
      return $privileges[$action];
    }

    return null;
  }

  protected function getDefaultActions()
  {
    $actions = parent::getDefaultActions();
    $actions[] = 'deleteConfirm';

    return $actions;
  }
}
