<?php

$data = array();

foreach ($notifications as $notification)
{
  $data[] = sa_api_notification($notification);
}

return array(
  'status' => 'success',
  'data' => $data
);
