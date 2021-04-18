<!DOCTYPE html>
<html>
<head>

<style>

form.file-input input {
    display: block;
    margin: 0 auto;
    margin-top: 20px;
}

div.center{
	margin:0 auto;
	margin-top:20px;
	text-align: center;
}
a.button,input[name='submit']{
	cursor:pointer;
  background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
}
</style>

</head>
<body>

<center>Select CSV to upload:</center>
<form class="file-input"  method="post" enctype="multipart/form-data">
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="Upload File" name="submit">
  <input type="hidden" vallud="check" name="filesubmittion">
</form>

<?php

function update_data( $city_name )
{

	$city_name = trim($city_name);
    $host = "localhost";
    $user = "roctoc_3rd";
    $password = "ookc2XqRozUb";
    $db_name = "roctoc_3rd";

    $DB = new mysqli($host, $user, $password, $db_name);


  	$query = "SELECT city_id,state_id FROM cities WHERE city_name = '" . $city_name . "'";

	  $response = mysqli_query($DB, $query);

	  if( $response->num_rows <= 0 ){
		  echo "<div class='center'>Unable to process the CSV. Can not find " . $city_name . ' in the database </div>';
		  die();
	  }

    $row = mysqli_fetch_array($response);

    return $row;

}

function updating_data($csv_file)
{

	echo "<div class='center'>Processing csv file...</div>";
    $handle = fopen( $csv_file , 'r');
	$new_file =  date("d-M-Y-H-i") . '-' . basename($csv_file );
    $writer = fopen( $new_file , 'w+');
    $data = array();
    while (($row = fgetcsv($handle)) !== false) {

		$city_name = $row[10];
		$fetch_data = update_data( $city_name );
		$city_id = $fetch_data[0];
		$state_id = $fetch_data[1];
        $newData = array();
        foreach ($row as $index => $value) {
            if ($index == 10) {
                $newData[] = $city_id;
            }elseif( $index == 11 ){
	            $newData[] = $state_id;
			}else{
				$newData[] = $value;
			}
        }
        $data[] = $newData;
    }

    foreach ($data as $fields) {

        fputcsv($writer, $fields);

    }

	fclose($handle);
	fclose($writer);

	unlink($csv_file);
	sleep(1);
	echo "<div class='center'>File processed successfully!</div>";
	echo "<div class='center'><a class='button' href='".$new_file."' download>Download</a></div>";

}

if (isset($_POST['filesubmittion'])) {
    $target_dir = "";

    $file = basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . $file;
    $uploadOk = 1;
    
    if ($file == null) {
        echo "<div class='center'><strong>You must provide a file</strong></div>";
        die();
    }

    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($imageFileType != "csv") {
            echo "<div class='center'><strong>Sorry, only CSV files are allowed.</strong></div>";
            $uploadOk = 0;
        } else {
            if (@move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "<div class='center'>The file <strong>" . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . "</strong> has been uploaded.</div>";

                updating_data($target_file);

            } else {
                echo "<div class='center'>Sorry, there was an error uploading your file.</div>";
            }
        }
    }
}
?>


</body>
</html>