<?php
require_once "connection.php";


		function getColorAway($var) {
				if ($var <= 0)
					return '#D7D7D7;';

				else if ($var >= 1)
					return '#334488';
			}

		function getColorBroken($var) {
				if ($var <= 0)
					return '#D7D7D7;';

				else if ($var >= 1)
					return '#e95522';
			}


	if(isset($_POST['option'])){
		$option = $_POST['option'];
	}

	if(isset($_POST['search'])){
		$search = $_POST['search'];
	}



	if(isset($option)) {
		if($option != 'alle') {
			$listquery = mysqli_query($connection, "SELECT COUNT(*) AS amount, ID, category, brand, serialnb, SUM(away), SUM(broken), location, comment, porte, hastighed, type, socket FROM komponenter WHERE category LIKE '" . $option . "' GROUP BY category, brand, porte, hastighed, type, socket");
		} elseif($option == 'alle') {
			$listquery = mysqli_query($connection, "SELECT COUNT(*) AS amount, ID, category, brand, serialnb, SUM(away), SUM(broken), location, comment, porte, hastighed, type, socket FROM komponenter GROUP BY category, brand, porte, hastighed, type, socket ORDER BY category ASC");
		}
		
	} elseif(isset($search)) {

		$search = mysqli_real_escape_string($connection, $search);
		$listquery = mysqli_query($connection, "SELECT COUNT(*) AS amount, ID, category, brand, serialnb, SUM(away), SUM(broken), location, comment, porte, hastighed, type, socket FROM komponenter WHERE category LIKE '%$search%' OR brand LIKE '%$search%' OR serialnb LIKE '%$search%' GROUP BY category, brand, porte, hastighed, type, socket");

	} elseif(!isset($option)&&!isset($search)) {

		$listquery = mysqli_query($connection, "SELECT COUNT(*) AS amount, ID, category, brand, serialnb, SUM(away), SUM(broken), location, comment, porte, hastighed, type, socket FROM komponenter GROUP BY category, brand, porte, hastighed, type, socket ORDER BY category ASC");
		
	}



	echo "<ul>";

		while ($row = mysqli_fetch_assoc($listquery)) {

			$away = $row['SUM(away)'];
			$broken = $row['SUM(broken)'];

			$category = $row['category'];

			
			switch($category) {
				case $category == "switch": $midsec = "Porte"; $midcat = $row['porte'];
					break;
				case $category == "router": $midsec = "Hastighed"; $midcat = $row['hastighed'];
					break;
				case $category == "sfp-modul": $midsec = "Type"; $midcat = $row['type'];
					break;
				case $category == "el-tavle": $midsec = "Type"; $midcat = $row['type'];
					break;
				case $category == "ram-blok": $midsec = "Type"; $midcat = $row['type'];
					break;
				case $category == "processor": $midsec = "Socket"; $midcat = $row['socket'];
					break;
				case $category == "kabel": $midsec = "Type"; $midcat = $row['type'];
					break;
				case $category == "motherboard": $midsec = "Socket"; $midcat = $row['socket'];
					break;

				default: $midsec = "?"; $midcat = "?";
			}
			
			echo "<div id='lidiv'>";
			echo "<li id=" . $row['ID'] . " class='liclass lilist'>";

				echo "<div id='kate'>" . $row['category'] . "</div>";

				echo "<div>" . " Mærke: " . $row['brand'] . "</div>";
				echo "<div>" . " " . $midsec . ": " . $midcat  . "</div>";
				echo "<div>" . " Antal: " . $row['amount'] . "</div>";

			
			echo "<br>";

				echo "<div class='status' id='firststatus' style='color: " . getColorAway($away) . "'>" . " Udlånte: " . $row['SUM(away)'] . "</div>";

				echo "<div class='status' style='color: " . getColorBroken($broken) . "'>" . " Ødelagte: " . $row['SUM(broken)'] . "</div>";


			echo "</li>";

			echo "<div id='div" . $row['ID'] . "' class='divID' style='display: none;'></div>";
			
			echo "</div>";
			
			echo "<hr>";

		}

	echo "</ul>";


mysqli_close($connection);
?>