<?php
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="./index.css">
</head>
<body>
<?php 
	@include 'display_data.php'; 
?>
<div class="database-display">
	<h2 class="table-title">customers</h2>
	<table id="customers-table">
		<tr id="row_id">
			<th>id</th>
			<th>first_name</th>
			<th>last_name</th>
			<th>age</th>
			<th>location</th>
		</tr>
	<?php echo displayTableData('customers') ?>
	</table>
	<h2 class="table-title">orders</h2>
	<table id="orders-table">
		<tr id="row_id">
			<th>id</th>
			<th>customers_id</th>
			<th>item_id</th>
		</tr>
	<?php echo displayTableData('orders') ?>
	</table>
	<h2 class="table-title">orders_index</h2>
	<table id="orders-index-table">
		<tr id="row_id">
			<th>id</th>
			<th>item</th>
		</tr>
	<?php echo displayTableData('orders_index') ?>
	</table>
</div>

<form action="reset_table.php" type="post">
	<button type="submit" name="reset">reset</button>
</form>

<h1>Enter Query</h1>
<form action="submit_query.php" method="post">
	<label for="query">Query</label>
	<input style="width:50%;" type="text" name="query"/>
	<button type="submit">submit</button>
</form>
<div class="query-msg">
	<?php
		if(isset($_SESSION["query_msg"])){
			echo $_SESSION["query_msg"];
		}
	?>
</div>
<h1>Query Result</h1>
<?php 
	if(isset($_SESSION["raw_data"])){
		echo "<pre>",print_r($_SESSION["raw_data"], true),"</pre>";
	}
?>
<h2>Examples</h2>
	<div class="example-container">
		<div class="insert-ex">
		<h3>Insert</h3>
		<code>INSERT INTO customers (first_name, last_name, age) VALUES ("jack", "smith", 33)</code>
		</div>
	<div class="query-ex">
		<h3>Querying</h3>
		<h4 class="task">Conditional Comparisons (<, >, =, <=, >=, !=) </h4>
		<code>SELECT * FROM customers WHERE age>20 AND last_name="smith"</code>
		<h4 class="task">Find all rows (IN) or not (NOT IN) matching a column's value</h4>
		<code>SELECT * FROM customers WHERE location NOT IN ("CA")</code>
		<h4 class="task">ORDER BY &lt;column&gt; ASC | DESC</h4>
		<code>SELECT * FROM customers ORDER BY age ASC</code>
		<h4 class="task">CASE...WHEN...THEN...END: Map column values to new values</h4>
		<code>SELECT first_name, 
			<br>CASE WHEN age < 21 THEN "Young" WHEN age > 28 THEN "Old" ELSE "Middle" END AS "Age Group" 
			<br>FROM customers 
			<br>ORDER BY age DESC
		</code>
	</div>
	<!-- filtering -->
	<div class="filter-ex">
		<h3>Filter Query</h3>
		<h4 class="task">LIKE: find results with matching string</h4>
		<p>Matching is case-insensitive</p>
		<p>"%" : match 0 or more characters in the column</p>
		<p>"_" : match a single character</p>
		<code>SELECT * FROM customers WHERE location LIKE "%CA%"</code>
		<h4 class="task">LIMIT: limit number of results returned</h4>
		<code>SELECT * FROM customers ORDER BY age DESC LIMIT 3</code>
	</div>
	<div class="aggregate-ex">
		<h3>Aggregate</h3>
		<h4 class="task">SELECT MAX|MIN|AVG|SUM|COUNT( [DISTINCT] *|column) FROM &lt;table&gt; [HAVING] {condition}</h4>
		<p>* DISTINCT removes duplicates, useful with COUNT</p>
		<p>* WHERE vs HAVING: WHERE filters on a row-by-row basis, HAVING filters by group</p>
		<code>SELECT MAX(age) FROM customers</code>
		<code>SELECT COUNT(DISTINCT last_name) FROM customers</code>
		<code>SELECT location, 
			<br>SUM(age) AS total_age
			<br>FROM customers
			<br>GROUP BY location
			<br>HAVING total_age > 50
		</code>
	</div>
	<div class="group-ex">
		<h4 class="task">Group Data</h4>
		<code>SELECT age, COUNT(*) FROM customers GROUP BY location</code>
	</div>
	<div class="join-ex">
		<h3>Joining Related Tables</h3>
		<p class="task">How to visualize JOINS:</p>
		<p>1) Create cross join (cartesian product) of the two tables, where EACH row from table 1 is compared with EACH row from table 2</p>
		<p>2) Evaluate each row, only keeping those where the ON clause returns TRUE</p>
		<p>3) For outer joins only: add back any rows that were lost(ie: for LEFT joins, if a row from the left table does not match with any rows from the right, add back that rows from left table that did not match, with NULL as the right column's value)</p>
		<!-- Inner Join -->
		<h4>Inner join for tables with matching columns in both tables</h4>
		<code>SELECT * FROM customers 
		<br>INNER JOIN orders ON customers.id = orders.customers_id</code>
		<!-- Left Outer Join -->
		<h4>Left outer join: returns all records from the left table, and matched records from the right </h4>
		<p>...FROM {left_table} JOIN {right_table} ON {matching conditions}</p>
		<p>writing LEFT OUTER JOIN equivalent to LEFT JOIN</p>
		<code>SELECT customers.first_name, customers.last_name, orders.item_id FROM customers 
		<br>LEFT OUTER JOIN orders ON customers.id = orders.customers_id</code>
		<!-- Left Excluding Join -->
		<h4>Left Exclude Join</h4>
		<p>Only returns results where row from left table have no matching values from the right table</p>
		<p>Returns customers_id that has no matching id value in CUSTOMERS table</p>
		<code>SELECT o.customers_id <br/>FROM orders o <br>LEFT JOIN customers c <br>ON o.customers_id = c.id <br>WHERE c.id IS NULL </code>
		<!-- Nested Join -->
		<h4>Nested Joins</h4>
		<p>Convert customers_id and item_id from orders table to its respective names</p>
		<code>SELECT customers.first_name, orders_index.item FROM orders <br/>INNER JOIN customers <br/>ON orders.customers_id = customers.id <br/>INNER JOIN orders_index <br/>ON orders.item_id = orders_index.id</code>
	</div>
	<!-- Update and Delete -->
	<div class="update-ex">
		<h3>Update Data</h3>
		<code>UPDATE customers SET location="GG" WHERE id=4</code>
		<h3>Delete Data</h3>
		<code>DELETE FROM customers WHERE id=5</code>
	</div>
	<!-- Core functions -->
	<div class="core-functions-ex">
		<h3><a href="https://www.sqlite.org/lang_corefunc.html">Core Functions</a></h3>
		<h4 class="task">Set of functions that comes with SQL</h4>
		<code>SELECT UPPER(first_name) FROM customers</code>
	</div>
	<!-- Union -->
	<div class="union-ex">
		<h3>UNION / UNION ALL</h3>
		<h4 class="task">Combine two seperate queries into a single column</h4>
		<p>Note: UNION vs UNION ALL: former filters out duplicate results. latter returns all results, including duplicates</p>
		<code>SELECT customers_id FROM orders UNION SELECT item FROM orders_index</code>
	</div>
</div>
<!-- Useful Resources -->
<h1>Resources</h1>
<a href="https://stackoverflow.com/questions/38549/what-is-the-difference-between-inner-join-and-outer-join">Understanding and Visualizing Joins</a>
<a href="https://www.sqlteaching.com/#!self_join">SQL Teaching</a>
<a href="https://www.codeproject.com/Articles/33052/Visual-Representation-of-SQL-Joins">Venn Diagram Visuals</a>

<script
  src="http://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous">
</script>
<script type="text/javascript">

	const codeDOMs = document.querySelectorAll('code');
	codeDOMs.forEach((dom) => {
	  dom.addEventListener('click', (e)=>{
			const query = e.target.innerText;
			submitQuery(query);
		})
	})
	
	// not working with Fetch api for some reason, use jquery ajax for now
	function submitQuery(query){
		let formData = new FormData();
		formData.append("query", query);
		$.ajax({
			url:'submit_query.php',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: (res) => {
			  location.reload();
			}
		});
	}

</script>

</body>
</html>