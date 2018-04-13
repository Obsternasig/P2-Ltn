<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Search Engine</title>
</head>

<body>
	<form action="searchformtest1.php" method="POST" autocomplete="off">
		<input type= "text" name= "search" placeholder="Search" />
		<button type="submit" name="submit-search">Search</button>
	</form>
	
</body>
</html>

<?php 
	require_once "../connection.php";

	if (isset($_POST['search'])) {
		$search = mysqli_real_escape_string($connection, $_POST['search']);
		/*forhindre MYSQl injection, så brugeren ikke skriver noget mærkeligt og ikke fucker med vores DB*/
		$sql = "SELECT * FROM komponenter WHERE category LIKE '%$search%' OR brand LIKE '%$search%'";
		/*Tager data fra tables*/
		
		$result = mysqli_query($connection, $sql);
		$queryResult = mysqli_num_rows($result);
		
		echo "Der er ".$queryResult." resultater ved det søgte!";
		
		if ($queryResult > 0) {
			while ($row = mysqli_fetch_assoc($result)){
				echo "<div>
					<h3>" .$row['category']."</h3> 
					<p>" .$row['brand']. "</p>
					</div>";
			}
		}
		
	}
	mysqli_close($connection);
?>
