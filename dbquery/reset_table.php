<?php
@include 'dbh.php';

session_destroy();
//////////////////////////////////////////////////
// ----- Clear Table and Reset Id to 1 ----- // //
//////////////////////////////////////////////////

function resetTable($table){
	@include 'dbh.php';

	$query = "DELETE FROM ".$table;
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$query = "ALTER TABLE ".$table." AUTO_INCREMENT = 1";
	$stmt = $conn->prepare($query);
	$stmt->execute();
}
resetTable('customers');
resetTable('orders');
resetTable('orders_index');
////////////////////////////////////////
// ----- Insert default data ----- // //
////////////////////////////////////////

// default data for customers
$customers_query = 'INSERT INTO customers (first_name, last_name, age, location) 
					VALUES ("john", "smith", 20, "CA"),("jane", "doe", 23, "AB"), ("bob", "jenkins", 28, "US"),("jack", "lee", 38, "CA")';
$stmt = $conn->prepare($customers_query);
$stmt->execute();

// default data for orders
$orders_query = 'INSERT INTO orders (customers_id, item_id) VALUES (1,1), (1, 2), (2,1), (3,3), (5, 4)';
$stmt = $conn->prepare($orders_query);
$stmt->execute();

// default data for orders_index
$orders_index_query = 'INSERT INTO orders_index (item) VALUES ("paper"), ("pen"), ("printer"), ("pencil")';
$stmt = $conn->prepare($orders_index_query);
$stmt->execute();

header('Location: index.php');
