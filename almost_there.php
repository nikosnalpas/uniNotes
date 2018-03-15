<?php 
require 'db.php';
session_start();
$_SESSION['userClicked'] = $_SESSION['idUser'];
?>
<!DOCTYPE html>
<html>
<head>
	<title>Unuo</title>
      <link rel="stylesheet" href="css/almost_there_style.css">
</head>

<body>


	<div class = "form">
		<h1>Almost threre!</h1>
		<div id="page-wrapper">
			<label>Select your university: </label>
			<input type="text" id="uniList" list="universities" oninput="showDepartments(this.value)">
			    <datalist id="universities">
			    	<?php

			    	
			    	$uni_array = $mysqli->query("SELECT * FROM universities");

                 



			    	foreach($uni_array as $uni){
			    		echo '<option value = "' . $uni['uniName'] . '"">';
			    		
			    	}	
			    	


			    	?>
	
			    </datalist>
			   
			</input>
		</div>
		<div id="page-wrapper" name="depBox">
			<label>Select your Department: </label>
			<input type="text" id="dList" list="departmentsList" oninput="showCourses(this.value)">
			    <datalist id="departmentsList">
			    	<option value = "First select a university from the list">
			    </datalist>
			</input>
		</div>
		<div id="page-wrapper">
			<label>Courses</label>
				
						 
			    <div id="coursesList">
			    	
			    </div>
			   

			
			
		</div>
		<button id="next" onclick="next()">
			NEXT
		</button>
		

	</div>
	  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="js/almost_there.js"></script>


</body>
</html>