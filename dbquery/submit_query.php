<?php
session_start(); 
@include "dbh.php";

try{
	$query = $_POST["query"];	
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC); // return results as association (aka linked) array
	if(count($result)){
		$_SESSION['query_msg'] = "Query success: ".$query;
		$_SESSION['raw_data'] = $result;
	}
	else{
	  $_SESSION['raw_data'] = "No Results";
	}
}
catch(PDOException $e)
{
  $_SESSION['query_msg']="Invalid Query";
	$_SESSION['raw_data'] = "Invalid Query: ".$e;

}


header('Location: index.php');
