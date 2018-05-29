<?php

function displayTableData($table){
  @include 'dbh.php';

  $query = 'SELECT * FROM '.$table;
  // prepare statement
  $stmt = $conn->prepare($query);
  // set params to fill in placeholders, in order of apperance
  // $params = array($_GET['id']);
  // execute query with param;
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // return results as association (aka linked) array
  if(count($result)){
    $display_data = '';
    if($table === 'customers'){
    foreach ($result as $row){
      	$display_data .= '<tr>'.
  				'<td>'.$row['id'].'</td>'.
  				'<td>'.$row['first_name'].'</td>'.
  				'<td>'.$row['last_name'].'</td>'.
  				'<td>'.$row['age'].'</td>'.
  				'<td>'.$row['location'].'</td>'.
  				'</tr>';
      }
    }
    else if($table === 'orders'){
    foreach ($result as $row){
        $display_data .= '<tr>'.
          '<td>'.$row['id'].'</td>'.
          '<td>'.$row['customers_id'].'</td>'.
          '<td>'.$row['item_id'].'</td>'.
          '</tr>';
      }
    }
    else if($table === 'orders_index'){
    foreach ($result as $row){
        $display_data .= '<tr>'.
          '<td>'.$row['id'].'</td>'.
          '<td>'.$row['item'].'</td>'.
          '</tr>';
      }
    }

    return $display_data;
  }
  else{
    return 'no results';
  }
}