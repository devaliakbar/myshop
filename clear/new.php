<?php
	include_once("conn.php");
	
	//CREATING ITEM VENDOR CREDIT
	$sql = "CREATE TABLE IF NOT EXISTS vendor_credit (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	vid INT,
	amount DECIMAL(12,3),
	returned DECIMAL(12,3),
	total DECIMAL(12,3),
	pay DECIMAL(12,3),
	balance DECIMAL(12,3)
	)";

	if (mysqli_query($conn, $sql)) {
		echo "<br>Table VENDOR CREDIT created successfully<br>";
	} else {
		echo "Error creating table: " . mysqli_error($conn);
	}
	
	//CREATING ITEM BILL CREDIT
	$sql = "CREATE TABLE IF NOT EXISTS bill_credit (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	billid INT,
	amount DECIMAL(12,3),
	returned DECIMAL(12,3),
	total DECIMAL(12,3),
	pay DECIMAL(12,3),
	balance DECIMAL(12,3)
	)";

	if (mysqli_query($conn, $sql)) {
		echo "<br>Table BILL CREDIT created successfully<br>";
	} else {
		echo "Error creating table: " . mysqli_error($conn);
	}
	
	//CREATING ITEM PAID HISTORY
	$sql = "CREATE TABLE IF NOT EXISTS payment (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	billid INT,
	amount DECIMAL(12,3),
	tdate DATE
	)";

	if (mysqli_query($conn, $sql)) {
		echo "<br>Table PAYMENT created successfully<br>";
	} else {
		echo "Error creating table: " . mysqli_error($conn);
	}
	
	//CREATING ITEM RETURN ITEM
	$sql = "CREATE TABLE IF NOT EXISTS ireturn (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	rdate DATE,
	billid INT,
	itemid INT,
	amount DECIMAL(12,3),
	hsn INT,
	cgstper DECIMAL(12,3),
	sgstper DECIMAL(12,3),
	igstper DECIMAL(12,3),
	cgst DECIMAL(12,3),
	sgst DECIMAL(12,3),
	igst DECIMAL(12,3),
	total DECIMAL(12,3),
	quantity INT,
	taxable DECIMAL(12,3),
	totcgst DECIMAL(12,3),
	totsgst DECIMAL(12,3),
	totigst DECIMAL(12,3),
	pay DECIMAL(12,3)
	)";
	if (mysqli_query($conn, $sql)) {
		echo "<br>Table RETURN created successfully<br>";
	} else {
		echo "Error creating table: " . mysqli_error($conn);
	}
	
	//CREATING Sales RETURN ITEM
	$sql = "CREATE TABLE IF NOT EXISTS sreturn (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	rdate DATE,
	billid INT,
	itemid INT,
	amount DECIMAL(12,3),
	hsn INT,
	cgstper DECIMAL(12,3),
	sgstper DECIMAL(12,3),
	igstper DECIMAL(12,3),
	cgst DECIMAL(12,3),
	sgst DECIMAL(12,3),
	igst DECIMAL(12,3),
	total DECIMAL(12,3),
	quantity INT,
	taxable DECIMAL(12,3),
	totcgst DECIMAL(12,3),
	totsgst DECIMAL(12,3),
	totigst DECIMAL(12,3),
	pay DECIMAL(12,3)
	)";
	if (mysqli_query($conn, $sql)) {
		echo "<br>Table Sales RETURN created successfully<br>";
	} else {
		echo "Error creating table: " . mysqli_error($conn);
	}
	
	
	//Closing
	mysqli_close($conn);
?>