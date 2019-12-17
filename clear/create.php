<?php
	$servername = "localhost";
	$username = "myshop";
	$password = "myshop";

	// Create connection
	$conn = mysqli_connect($servername, $username, $password);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	// Create database
	$sql = "CREATE DATABASE IF NOT EXISTS myshop;";
	if (mysqli_query($conn, $sql)) {
		echo "Database created successfully<br>";
	} else {
		echo "Error creating database: " . mysqli_error($conn);
	}

	mysqli_close($conn);


	include_once("conn.php");
	//CREATING ITEM TABLE
	$sql = "CREATE TABLE IF NOT EXISTS item (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	name VARCHAR(50),
	catagory VARCHAR(50),
	manufactor VARCHAR(50)
	)";

	if (mysqli_query($conn, $sql)) {
		echo "<br>Table item created successfully<br>";
	} else {
		echo "Error creating table: " . mysqli_error($conn);
	}
	
	//CREATING ITEM AMOUNT
	$sql = "CREATE TABLE IF NOT EXISTS amount (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	itemid INT,
	type VARCHAR(10),
	price DECIMAL(12,3),
	cgst DECIMAL(12,3),
	sgst DECIMAL(12,3),
	igst DECIMAL(12,3),
	total DECIMAL(12,3)
	)";

	if (mysqli_query($conn, $sql)) {
		echo "<br>Table amount created successfully<br>";
	} else {
		echo "Error creating table: " . mysqli_error($conn);
	}
	
	//CREATING ITEM TAX
	$sql = "CREATE TABLE IF NOT EXISTS tax (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	itemid INT,
	hsn INT,
	cgstper DECIMAL(12,3),
	sgstper DECIMAL(12,3),
	igstper DECIMAL(12,3)
	)";

	if (mysqli_query($conn, $sql)) {
		echo "<br>Table tax created successfully<br>";
	} else {
		echo "Error creating table: " . mysqli_error($conn);
	}
	
	//CREATING ITEM STOCK
	$sql = "CREATE TABLE IF NOT EXISTS stock (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	itemid INT,
	bal INT,
	sold INT
	)";

	if (mysqli_query($conn, $sql)) {
		echo "<br>Table stock created successfully<br>";
	} else {
		echo "Error creating table: " . mysqli_error($conn);
	}
	
	//CREATING ITEM PURCHASE BILL
	$sql = "CREATE TABLE IF NOT EXISTS purchase_bill (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	vbid VARCHAR(15),
	dates DATE,
	vendorid INT,
	amount DECIMAL(12,3),
	cgstper DECIMAL(12,3),
	sgstper DECIMAL(12,3),
	igstper DECIMAL(12,3),
	cgst DECIMAL(12,3),
	sgst DECIMAL(12,3),
	igst DECIMAL(12,3),
	total DECIMAL(12,3)
	)";

	if (mysqli_query($conn, $sql)) {
		echo "<br>Table purchase_bill created successfully<br>";
	} else {
		echo "Error creating table: " . mysqli_error($conn);
	}
	
	//CREATING ITEM PURCHASE ITEM
	$sql = "CREATE TABLE IF NOT EXISTS purchase_item (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	purchaseid INT,
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
		echo "<br>Table purchase_item created successfully<br>";
	} else {
		echo "Error creating table: " . mysqli_error($conn);
	}
	
	//CREATING VENDOR
	$sql = "CREATE TABLE IF NOT EXISTS vendor (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	name VARCHAR(25),
	phone VARCHAR(35),
	address VARCHAR(50),
	gst VARCHAR(100)
	)";

	if (mysqli_query($conn, $sql)) {
		echo "<br>Table Vendor created successfully<br>";
	} else {
		echo "Error creating table: " . mysqli_error($conn);
	}
	
	//CREATING ITEM SALES BILL
	$sql = "CREATE TABLE IF NOT EXISTS sales_bill (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	dates DATE,
	custid INT,
	amount DECIMAL(12,3),
	cgstper DECIMAL(12,3),
	sgstper DECIMAL(12,3),
	igstper DECIMAL(12,3),
	cgst DECIMAL(12,3),
	sgst DECIMAL(12,3),
	igst DECIMAL(12,3),
	total DECIMAL(12,3),
	discount DECIMAL(12,3),
	discountper DECIMAL(12,3),
	pay DECIMAL(12,3)
	)";

	if (mysqli_query($conn, $sql)) {
		echo "<br>Table Sales Bill created successfully<br>";
	} else {
		echo "Error creating table: " . mysqli_error($conn);
	}
	
	//CREATING ITEM SALES ITEM
	$sql = "CREATE TABLE IF NOT EXISTS sales_item (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
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
	mrp DECIMAL(12,3),
	total DECIMAL(12,3),
	quantity INT,
	taxable DECIMAL(12,3),
	totcgst DECIMAL(12,3),
	totsgst DECIMAL(12,3),
	totigst DECIMAL(12,3),
	totmrp DECIMAL(12,3),
	discount DECIMAL(12,3),
	discountper DECIMAL(12,3),
	pay DECIMAL(12,3)
	)";

	if (mysqli_query($conn, $sql)) {
		echo "<br>Table Sakes Item created successfully<br>";
	} else {
		echo "Error creating table: " . mysqli_error($conn);
	}
	
	//CREATING CUSTOMER
	$sql = "CREATE TABLE IF NOT EXISTS customer (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	name VARCHAR(25),
	phone VARCHAR(20),
	address VARCHAR(100),
	gst VARCHAR(100)
	)";

	if (mysqli_query($conn, $sql)) {
		echo "<br>Table Customer created successfully<br>";
	} else {
		echo "Error creating table: " . mysqli_error($conn);
	}
	
	
		//insert na CUSTOMER
	$sql = "INSERT INTO `customer`(`id`, `name`, `phone`, `address`, `gst`) VALUES ('1','NA','NA','NA','NA');";

	if (mysqli_query($conn, $sql)) {
		echo "<br>Table Customer inserted successfully<br>";
	} else {
		echo "Error insert table: " . mysqli_error($conn);
	}
	
	
	//insert na vendor
	$sql = "INSERT INTO `vendor`(`id`, `name`, `phone`, `address`, `gst`) VALUES ('1','NA','NA','NA','NA');";

	if (mysqli_query($conn, $sql)) {
		echo "<br>Table vendor inserted successfully<br>";
	} else {
		echo "Error insert table: " . mysqli_error($conn);
	}
	
	
	//Closing
	mysqli_close($conn);