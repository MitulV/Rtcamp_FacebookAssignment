<?php
session_start();
require 'Facebook/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => '229784471015930', 
  'app_secret' => '7d6874495289f25bb1121373d93abba1',
  'default_graph_version' => 'v3.1',
  ]);

$appId='229784471015930';
$appSecret='7d6874495289f25bb1121373d93abba1';
$helper = $fb->getRedirectLoginHelper();

?>