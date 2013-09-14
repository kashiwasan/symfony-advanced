<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * PartsHelper.
 *
 * @package    sfadvanced
 * @subpackage helper
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @author     Rimpei Ogawa <ogawa@tejimaya.com>
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */

/**
 * Include parts
 *
 * @param string $name parts name
 * @param string $id
 * @param array  $sations
 */
function sa_include_parts($name, $id, $sations = array())
{
  $params = array(
    'id'      => $id,
    'name'    => $name,
    'sations' => new saPartsOptionHolder($sations),
  );

  $params['sa_content'] = get_partial('global/parts'.ucfirst($name), $params);

  if ('' === trim($params['sa_content']))
  {
    return;
  }

  include_partial('global/partsLayout', $params);

  $shorts = $params['sations']->getShortRequiredOptions();
  if ($shorts)
  {
    throw new LogicException(sprintf('The %s parts requires the following sations: \'%s\'.', $name, implode('\', \'', $shorts)));
  }
}

/**
 * Include box parts
 *
 * @param string $id
 * @param string $body
 * @param array  $sations
 *
 * @see sa_include_parts
 */
function sa_include_box($id, $body, $sations = array())
{
  $sations['body'] = $body;

  sa_include_parts('box', $id, $sations);
}

/**
 * Include form parts
 *
 * @param string $id
 * @param mixed  $form  a sfForm object or an array of sfForm objects
 * @param array  $sations
 *
 * @see sa_include_parts
 */
function sa_include_form($id, $form, $sations = array())
{
  $sations['form'] = $form;

  sa_include_parts('form', $id, $sations);
}

/**
 * Include list parts
 *
 * @param string $id
 * @param array  $list
 * @param array  $sations
 *
 * @see sa_include_parts
 */
function sa_include_list($id, $list, $sations = array())
{
  $sations['list'] = $list;

  sa_include_parts('list', $id, $sations);
}

/**
 * Include line parts
 *
 * @param string $id
 * @param string $line
 * @param array  $sations
 *
 * @see sa_include_parts
 */
function sa_include_line($id, $line, $sations = array())
{
  $sations['line'] = $line;

  sa_include_parts('line', $id, $sations);
}

/**
 * Include yesNo parts
 *
 * @params string $id
 * @params mixed  $yesForm a sfForm object or array of sfForm objects
 * @params mixed  $noForm  a sfForm object or array of sfForm objects
 * @params array  $sations
 */
function sa_include_yesno($id, $yesForm, $noForm, $sations = array())
{
  $sations['yes_form'] = $yesForm;
  $sations['no_form'] = $noForm;

  sa_include_parts('yesNo', $id, $sations);
}

/**
 * Gets customizes.
 *
 * @param string $id
 * @param string $name
 */
function get_customizes($id, $name, $vars = null)
{
  $context = sfContext::getInstance();

  $viewInstance = $context->get('view_instance');
  $customizes = $viewInstance->getCustomize('', $id, $name);
  $lastActionStack = $context->getActionStack()->getLastEntry();

  $content = '';
  foreach ($customizes as $customize) {
    $moduleName = $customize[0];
    if (!$moduleName) {
      $moduleName = $lastActionStack->getModuleName();
    }
    $actionName = $customize[1];
    if (!isset($vars))
    {
      $vars = $lastActionStack->getActionInstance()->getVarHolder()->getAll();
    }
    if ($customize[2])
    {
      $content .= get_component($moduleName, $actionName, $vars);
    }
    else
    {
      $templateName = $moduleName.'/'.$actionName;
      $content .= get_partial($templateName, $vars);
    }
  }

  return $content;
}

/**
 * Includes customizes.
 *
 * @param string $id
 * @param string $name
 */
function include_customizes($id, $name, $vars = null)
{
  echo get_customizes($id, $name, $vars);
}

/**
 * Set the sa_mobile_header slot
 *
 * @param string $title
 * @param string $subtitle
 */
function sa_mobile_page_title($title, $subtitle = '')
{
  $params = array(
    'title' => sfOutputEscaper::unescape($title),
    'subtitle' => sfOutputEscaper::unescape($subtitle),
  );

  slot('sa_mobile_header', get_partial('global/partsPageTitle', $params));
}

/**
 * Includes a login parts.
 *
 * @param string $id
 * @param sfForm $form
 * @param string $link_to   A location of an action.
 *
 * @see    include_partial
 */
function include_login_parts($id, $form, $link_to)
{
  $params = array(
    'id' => $form->getAuthMode().$id,
    'form' => $form,
    'link_to' => url_for(sprintf($link_to.'?%s=%s', saAuthForm::AUTH_MODE_FIELD_NAME, $form->getAuthMode())),
  );
  include_partial('global/partsLogin', $params);
}

/**
 * @deprecated since 3.0beta4
 */
function include_page_title($title, $subtitle = '')
{
  use_helper('Debug');
  log_message('include_page_title() is deprecated.', 'err');

  $params = array(
    'title' => $title,
    'subtitle' => $subtitle,
  );
  include_partial('global/partsPageTitle', $params);
}

/**
 * @deprecated since 3.0beta4
 */
function include_list_box($id, $list, $sations = array())
{
  use_helper('Debug');
  log_message('include_list_box() is deprecated.', 'err');

  $sations['list'] = $list;

  sa_include_parts('listBox', $id, $sations);
}

/**
 * @deprecated since 3.0beta4
 */
function include_simple_box($id, $title = '', $block = '', $sations = array())
{
  use_helper('Debug');
  log_message('include_simple_box() is deprecated.', 'err');

  if(!isset($sations['border']))
  {
    $sations['border'] = true;
  }
  if(!isset($sations['class']))
  {
    $sations['class'] = '';
  }

  $params = array(
    'id' => $id,
    'title' => $title,
    'block' => $block,
    'sations' => $sations,
  );

  include_partial('global/partsSimpleBox', $params);
}

/**
 * @deprecated since 3.0beta4
 */
function include_box($id, $title = '', $body = '', $sations = array())
{
  use_helper('Debug');
  log_message('include_box() is deprecated.', 'err');

  $sations['title'] = $title;

  if (!empty($sations['form']))
  {
    if ($body)
    {
      $sations['info'] = $body;
    }

    if (!isset($sations['button']))
    {
      $sations['button'] = '変更';
    }

    if (!isset($sations['url']))
    {
      $request = sfContext::getInstance()->getRequest();
      $sations['url'] = $request->getParameter('module').'/'.$request->getParameter('action');
    }
    else
    {
      $sations['url'] = url_for($sations['url']);
    }

    sa_include_form($id, $sations['form'], $sations);
  }
  else
  {
    sa_include_box($id, $body, $sations);
  }
}

/**
 * @deprecated since 3.0beta4
 */
function include_parts($parts_name, $id, $sation = array())
{
  use_helper('Debug');
  log_message('include_parts() is deprecated.', 'err');

  $params = array(
    'id'      => $id,
    'sations' => $sation,
  );
  include_partial('global/parts'.ucfirst($parts_name), $params);
}

/**
 * Include news pager
 *
 * @deprecated since 3.0beta4
 */
function include_news_pager($id, $title = '', $pager, $list, $link_to_detail)
{
  use_helper('Debug');
  log_message('include_news_pager() is deprecated.', 'err');

  $params = array(
    'id' => $id,
    'title' => $title,
    'pager' => $pager,
    'list' => $list,
    'link_to_detail' => $link_to_detail,
  );

 include_simple_box( $id, $title, get_partial('global/partsNewsPager', $params), array('class' => 'partsNewsPager'));
}
