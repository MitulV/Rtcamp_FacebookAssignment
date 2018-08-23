<head>
  <title>Album Photos</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
<?php 
include 'header.php';
require 'config.php';

$album_id=$_GET['album_id'];
$album_name=$_GET['album_name'];
$accessToken=$_GET['accessToken'];
$_SESSION['album_id']=$album_id;

echo '<h1><span style="margin-top:3%;margin-left:20%;display:block;width:60%" class="label label-primary">'.$album_name.'</span></h1><br>';

if ($_GET['run'] == 'yes') {
    download($album_id,$accessToken,$album_name);
} else 
{
    echo '<a  href="photos.php?album_id='.$album_id.'&album_name='.$album_name.'&accessToken='.$accessToken.'&run=yes"><button style="margin-left:37%" type="button" class="btn btn-success btn-md">Download</button></a>';
}
    echo '<a  href="backup.php?album_id='.$album_id.'"><button style="margin-left:3%" type="button" class="btn btn-danger btn-md">Backup to Google Drive</button></a><br>';



$graphPhoLink = "https://graph.facebook.com/v3.1/".$album_id."/photos?fields=source,images,name&access_token=".$accessToken."";
$jsonData = file_get_contents($graphPhoLink);
$fbPhotoObj = json_decode($jsonData, true, 512, JSON_BIGINT_AS_STRING);
$fbPhotoData = $fbPhotoObj['data'];
$i=0;
echo '<div id="myCarousel" style="margin-top:3%;" class="carousel slide" data-ride="carousel">
	<div class="carousel-inner" >';
  foreach ($fbPhotoData as $data) 
  {
    	$image_obj=end($data['images']);
    	$source_url=isset($image_obj['source'])?$image_obj['source']:'';
    	$height=isset($image_obj['height'])?$image_obj['height']:'';
     	$width=isset($image_obj['width'])?$image_obj['width']:'';
       
      $i++;
    	if($i==1)
    	{
        echo '  <div class="item active" style="margin-left:35%;">
            <img src="'.$source_url.'" style="box-shadow:2px 4px 8px black" alt="img"  >
          </div>';
      }
      else
      {
    	echo '
          <div class="item" style="margin-left:35%;">
            <img src="'.$source_url.'" alt="img" style="box-shadow:2px 4px 8px grey" >
          </div>';
      }  

  }
echo '</div>';
echo '<a class="left carousel-control"  href="#myCarousel" data-slide="prev" style="background:white ">
      <span class="glyphicon glyphicon-chevron-left" style="color:black"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next" style="background:white">
      <span class="glyphicon glyphicon-chevron-right" style="color:black"></span>
      <span class="sr-only">Next</span>
    </a>';

 function download($album_id,$accessToken,$album_name)
{
  $graphPhoLink = "https://graph.facebook.com/v3.1/".$album_id."/photos?fields=source,images,name&access_token=".$accessToken."";
  $jsonData = file_get_contents($graphPhoLink);
  $fbPhotoObj = json_decode($jsonData, true, 512, JSON_BIGINT_AS_STRING);
  $fbPhotoData = $fbPhotoObj['data'];
$i=1;
  
  foreach ($fbPhotoData as $data) 
  {
    	$image_obj=end($data['images']);
    	$source_url=isset($image_obj['source'])?$image_obj['source']:'';
    	$height=isset($image_obj['height'])?$image_obj['height']:'';
     	$width=isset($image_obj['width'])?$image_obj['width']:'';

      if(!file_exists('images/album-'.$album_id.'')){
      mkdir('images/album-'.$album_id.'');}
      $img = 'images/album-'.$album_id.'/'.$album_name.'-'.$i.'.jpg';
      file_put_contents($img, file_get_contents($source_url)); 
      $i++;	
  }

$album_id=$_GET['album_id'];
$dir='images/album-'.$album_id.'/';
$zip_name='images/album-'.$album_id.'.zip';
$zip=new ZipArchive;
  if($zip ->open($zip_name,ZipArchive::CREATE)==TRUE)
	{
		  $new_dir=opendir($dir);
		  while ($file=readdir($new_dir))
      {
			     if(is_file($dir.$file))
			   {
			   	 $zip ->addFile($dir.$file,$file);
			   }
		  }
		
          $zip ->close();
        if(file_exists($zip_name))
        {
            header('Content-Disposition: attachment; filename="'.$zip_name.'"');  
            readfile($zip_name); 
            header("Content-Type: application/zip");
            header("Content-Transfer-Encoding: binary");
            exit;
        }
		echo "<h1  style='text-align:center;color:green;font-family:Georgia'>Success</h1>";
	}
	
}
?>
</body>