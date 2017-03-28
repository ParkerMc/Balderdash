<?php
include 'user.php';
include 'config.php';
checkLogin();
if(isset($_POST["w1"])){
	$username = getUsername();
	$link = mysqli_connect($db["hostname"], $db["username"], $db["password"], $db["database"])
  	or die("<script>alert(\"Error connecting to database.\");</script>");
  	$query = "INSERT INTO `words` (`uid`, `word`, `def`, `username`) VALUES";
  	$query .= "(NULL, '".strtolower($_POST["w1"])."', '".$_POST["d1"]."', '".$username."')";
	for($i=2;$i<=$_POST["num"];$i++){
		if(!empty($_POST["w".$i])&&!empty($_POST["d".$i])){
			$query2 = "SELECT word from words where word='" . $_POST["w".$i] . "'";
		    $result = mysqli_query($link, $query2);
		    if(mysqli_num_rows($result) < 1){
				$query .= ", (NULL, '".strtolower($_POST["w".$i])."', '".$_POST["d".$i]."', '".$username."')";
			}
		}
	}
	$result = mysqli_query($link, $query) or die("<script>alert(\"Error adding words.\");</script>");
	mysqli_close($link);
	echo "words added";
}
?>
<html>
	<head>
		<title>Stage 1</title>
		<script>
			var numRows = 3;
			function addRow(){
				numRows += 1;
				var table = document.getElementById("table");
				var row = table.insertRow(numRows + 1);
				row.id = numRows + 1;
				var cell1 = row.insertCell(0);
				var cell2 = row.insertCell(1);
				cell1.innerHTML = "<a onclick=\"addRow()\">Add Row</a>";
				cell2.innerHTML = "<input type=\"submit\" value=\"Submit\"/>";
				
				var row = document.getElementById(numRows);
				row.innerHTML = "<th><input type=\"text\" name=\"w"+numRows+"\" id=\"w"+numRows+"\"/></th>";
				row.innerHTML += "<th><textarea rows=\"3\" cols=\"50\" name=\"d"+numRows+"\" id=\"d"+numRows+"\"></textarea></th>";
				
				var numField = document.getElementById("num");
				numField.value = numRows;
			}
		</script>
	</head>
	<body>
		<form action="stage1.php" method="post">
			<input type="hidden" id="num" name="num" value="3"/>
			<table id="table">
  				<tr id="0">
  					<th>Word</th>
  					<th>Definition</th>
  				</tr>
  				<tr id="1">
  					<th><input type="text" name="w1" id="w1" required/></th>
	  				<th><textarea rows="3" cols="50" name="d1" id="d1" required></textarea></th>
  				</tr id="2">
  					<th><input type="text" name="w2" id="w2"/></th>
  					<th><textarea rows="3" cols="50" name="d2" id="d2"></textarea></th>
  				<tr id="3">
  					<th><input type="text" name="w3" id="w3"/></th>
  					<th><textarea rows="3" cols="50" name="d3" id="d3"></textarea></th>
	  			</tr>
  				<tr id="4">
  					<th><a onclick="addRow()">Add Row</a></th>
  					<th><input type="submit" value="Submit"/></th>
	  			</tr>
  			</table>
  		</form>
	</body>
</html>