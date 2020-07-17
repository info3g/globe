<?php
$localhost = 'localhost';
$username = 'utzx9q3fbhejg';
$password = 'wy4hp5a35ggn';
$database = 'db38mpuckyvfeq';
$tableName = 'PixelInstaller';
$con = mysqli_connect($localhost,$username,$password,$database);

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Create table query
if(mysqli_query($con,"DESCRIBE PixelInstaller")) {
	//echo 'Table already Exist';
} else {
	$sql = "CREATE TABLE PixelInstaller(
		id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
		access_token VARCHAR(255) NOT NULL,
		shop_url VARCHAR(255) NOT NULL,
		charge_id VARCHAR(255) NOT NULL,
		active VARCHAR(255) NOT NULL,
		timestamp VARCHAR(255) NOT NULL
		)";
	
	if(mysqli_query($con, $sql)) {
		//echo "Table created successfully.";
	} else {
		echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
	}
}

?>