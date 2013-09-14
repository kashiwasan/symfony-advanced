<?php

$data = array();

foreach ($members as $member)
{
  $data[] = sa_api_member($member);
}

return array(
  'status' => 'success',
  'data' => $data,
);
