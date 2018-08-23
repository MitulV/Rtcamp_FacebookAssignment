<?php include 'header.php'; ?>
<head>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <title>Profile Albums</title>
    <style type="text/css">
      body{
        margin:0px;
        padding: 0px;
      }
      .fb-album
      {      
        margin-left: 5%;
        margin-top: 2%;
        float: left;
        width:330px;
        
        height: 410px;
      }
      .mybody{
        width:100%;
        clear:left;
        
      }
    </style>
    <script>
      function f()
      {
        document.getElementById('p').style.visibility="visible";
        setTimeout(function f(){document.getElementById('p').style.visibility="hidden";},5000);
      }
    </script>
</head>  

<body>    
  <div class="mybody" ">
<div class="progress" id="p" style="visibility: hidden;">
    <div class="progress-bar progress-bar-striped active"  role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:100%;margin-top: 10px;position: absolute;height: 5%">
    </div>
  </div>
<?php 

require 'config.php';

try {
  $accessToken = $helper->getAccessToken();

} catch(Facebook\Exceptions\FacebookResponseException $e) {

  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {

  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$oAuth2Client = $fb->getOAuth2Client();


$tokenMetadata = $oAuth2Client->debugToken($accessToken);

error_reporting(E_ALL ^ E_NOTICE);

$tokenMetadata->validateAppId('229784471015930'); 

$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {

  try {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
    exit;
  }

  echo '<h3>Long-lived</h3>';
  var_dump($accessToken->getValue());
}
$_SESSION['fb_access_token'] = (string) $accessToken;
$response=$fb->get("me/?fields=id,name,albums",$accessToken);

$user=$response->getGraphUser()->asArray();
echo "<pre>";
$a=$user['albums'];
$user_id=$user['id'];

   $graphActLink = "https://graph.facebook.com/oauth/access_token?client_id=".$appId."&client_secret=".$appSecret."&grant_type=client_credentials";
    $accessTokenJson = file_get_contents($graphActLink);
    $accessTokenObj = json_decode($accessTokenJson);
    $access_token = $accessTokenObj->access_token;
$_SESSION['facebook_access_token'] = $accessToken;

$fields = "id,name,description,link,cover_photo,count";
$graphAlbLink = "https://graph.facebook.com/v3.1/".$user_id."/albums?fields=".$fields."&access_token=".$accessToken."";


$jsonData = file_get_contents($graphAlbLink);
$fbAlbumObj = json_decode($jsonData, true, 512, JSON_BIGINT_AS_STRING);
$fbAlbumData = $fbAlbumObj['data'];
$al=0;
foreach($fbAlbumData as $data){
    $id = isset($data['id'])?$data['id']:'';
    $name = isset($data['name'])?$data['name']:'';
    $description = isset($data['description'])?$data['description']:'';
    $link = isset($data['link'])?$data['link']:'';
    $cover_photo_id = isset($data['cover_photo']['id'])?$data['cover_photo']['id']:'';
    $count = isset($data['count'])?$data['count']:'';
     $array[$al]=$id;
     $al++;

 ?>
     <div class="fb-album" style=""><?php
  
  echo '<a href="photos.php?album_id='.$id.'&album_name='.$name.'&accessToken='.$accessToken.'&run&backup" target="_blank">';
    echo '<img id="photo" src="https://graph.facebook.com/v3.1/'.$cover_photo_id.'/picture?access_token='.$accessToken.'" style="margin:2px;box-shadow:1px 3px 10px 2px grey" height="292" width="325" >';
    echo '</a>';
    $photoCount = ($count > 1)?$count:$count. 'Photo';
    echo '<a style="text-decoration:none;" href="photos.php?album_id='.$id.'&album_name='.$name.'&accessToken='.$accessToken.'&run&backup" target="_blank">
    <button type="button" class="btn btn-primary btn-block">'.$name.' <span class="badge">'.$photoCount.'</span></button></a>
    ';
   echo '<a href="photos.php?album_id='.$id.'&album_name='.$name.'&accessToken='.$accessToken.'&run=yes"><button style="margin-right:1%;" type="button" onclick="f()" class="btn btn-success">Download</button></a>';
    
    echo '<a  href="backup.php?album_id='.$album_id.'"><button style="margin-left:1%" type="button" class="btn btn-danger">Backup to Google Drive</button></button></a>';
    echo "</div>";
}
?>

</div>
</body>