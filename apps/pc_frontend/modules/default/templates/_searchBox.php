<?php sa_include_parts('searchFormLine', 'searchLine_'.$gadget->getId(), array(
  'button' => __('Search'),
  'items' => array(
    'member' => __('Member'),
    'community' => __('%community%', array('%community%' => $sa_term['community']->titleize())),
  ),
)) ?>
