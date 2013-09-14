<?php

$data = array();

foreach ($communities as $community)
{
  $data[] = sa_api_community($community);
}

return array(
  'status' => 'success',
  'data' => $data,
);
