<?php
session_start();
$album_id=$_SESSION['album_id'];
require_once 'google/src/Google_Client.php';
require_once 'google/src/contrib/Google_DriveService.php';
$client = new Google_Client();
$client->setClientId('324272734135-qvll5gqej5p1apvdo6ukdhdmi376012g.apps.googleusercontent.com');
$client->setClientSecret('MeUfzJcYfwXL4fCoeQL4aqy3');
$client->setRedirectUri('http://localhost/facebook_login/backup.php');
$client->setScopes(array('https://www.googleapis.com/auth/drive'));

   $service = new Google_DriveService($client);
   $authUrl = $client->createAuthUrl();
   $authCode = $_GET['code'];
   $accessToken = $client->authenticate($authCode);
   $client->setAccessToken($accessToken);

$file = new Google_DriveFile();
$localfile='images/album-'.$album_id.'.zip';
$title = basename($localfile);
$file->setTitle($title);
$file->setDescription('My File');
$file->setMimeType('application/zip');
$data = file_get_contents($localfile);
    $createdFile = $service->files->insert($file, array(
          'data' => $data,
          'mimeType' => 'application/zip',
        ));
header('location:success.php');
?>