<h1>PHP Multiple Images Upload Example</h1>

<?php


if(isset($_GET["delete"])){
	if(file_exists("pictures/" . $_GET["delete"])){
		unlink("pictures/" . $_GET["delete"]);
		echo "<div class='alert'>A picture has been deleted.</div>";
	}
}


//Creating pictures folder
if(!file_exists("pictures"))
	mkdir("pictures");

if(isset($_POST["submitmorepictures"])){
	
	include("thumbnailgenerator.php");
	
	$files = array_filter($_FILES['newmorepicture']['name']);
	$total = count($files);
	
	$hasfile = false;

	// Loop through each file
	for( $i=0 ; $i < $total ; $i++ ) {

		//Get the temp file path
		$tmpFilePath = $_FILES['newmorepicture']['tmp_name'][$i];

		//Make sure we have a file path
		if ($tmpFilePath != ""){
		  
		  
			$maxsize = 524288;
			
			$extsAllowed = array( 'jpg', 'jpeg', 'png' );
			$uploadedfile = $_FILES['newmorepicture']['name'][$i];
			$extension = pathinfo($uploadedfile, PATHINFO_EXTENSION);
			if (in_array($extension, $extsAllowed) ) { 
				$newpicture = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 10);
				$name = "pictures/" . $newpicture .".". $extension;
				
				if(($_FILES['newmorepicture']['size'][$i] >= $maxsize)){
					createThumbnail($_FILES['newmorepicture']['tmp_name'][$i], "pictures/" . $newpicture .".". $extension, 512);
				}else{
					$result = move_uploaded_file($_FILES['newmorepicture']['tmp_name'][$i], $name);
				}
				
				$hasfile = true;
			}
		}
	}
	if($hasfile)
		echo "<div class='alert'>More picture(s) has been added.</div>";
}


$dirpath = "pictures/*";
$files = array();
$files = glob($dirpath);
usort($files, function($x, $y) {
	return filemtime($x) < filemtime($y);
});

foreach($files as $item){
	echo "<div style='display: inline-block; vertical-align: top; text-align: center;'>";
	echo "<div><img src='" . $item . "' height='128px' style='margin: 5px; border-radius: 5px; cursor: pointer;' onclick=showimage('" . $item . "')></div>";
	echo "<a class='textlink' href='?pictures&delete=" . explode("/", $item)[1] . "'><i class='fa fa-trash'></i> Delete</a></div>";
}

?>
<div style="margin-top: 50px">
	<form method="post" enctype="multipart/form-data">
		<label><i class="fa fa-image"></i> Add more picture</label>
		<input class="fileinput" name="newmorepicture[]" type="file" accept="image/jpeg, image/png" multiple="multiple">
		<input name = "submitmorepictures" type="submit" value="Submit" class="submitbutton">
	</form>
</div>
<?php
