<?php
session_start();
if ( $_SESSION['logged_in'] != 1 ) {
  header("location: index.php");    
}
else {
    require 'db.php';
    require 'getProfileInfo.php';
    $idUser = $_SESSION['idUser'];
    $profilName = $_SESSION['profileUsername'];
    $uniName = $_SESSION['profileUni'];
    $idUniversitie = $_SESSION['idUni'];
    $depName = $_SESSION['profileDep'];
    $idDepartment = $_SESSION['idDep'];
    $idClasses = $_SESSION['profileClasses'];
    $idClass = $_SESSION['classClicked'];
    $profPic = $_SESSION['profPic'];
    $profPicType = $_SESSION['profPicType'];
    $username = $_SESSION['username'];
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>uniNotes</title>
  <link rel="stylesheet" href="css/mainStyle.css">
</head>
<body>
<div id="top-wrapper">
      <img src="css/images&shit/logo.png" class="logo" onclick="mainLogoClick()">
      <input type="text" placeholder = "Search Courses" oninput="showAddCourse()" list = "coursesInList" id="coursesList">
			    <datalist id="coursesInList">
			    	<?php			    	
			    	$coursesArray = $mysqli->query("SELECT * FROM classes WHERE Departments_idDepartments='$idDepartment'");                
			    	foreach($coursesArray as $course){
			    		echo '<option value = "' . $course['className'] . '">';			    		
			    	}
			    	echo '<option value = "asdasdasd">';			
			    	?>	
			    </datalist>	
      <button class = "addCourse">add</button>			    		  
      <div class = "top-container">
      <div class = "locationButtons">
      	<div class = "mainPageButtonWrapper">
      		<label class = "mainPageLabel" onclick = "mainPage()">maing page</label>
      	</div>
      	<div class = "readLaterButtonWrapper">
      		<label class = "readLaterLabel">read later</label>
      	</div>
      	

      	
      </div>
      <div class = "username-profPic-wrapper">
      <?php
      if($_SESSION['profPic']=='0'){
              echo '<img src="css/images&shit/user.png" id="top-profPic" onclick="profileClick('.$idUser.')">';
         }else{

               echo '<img src="data:'.$profPicType.';base64,'.base64_encode($profPic).'" id="top-profPic" onclick="profileClick('.$idUser.')"/>'; 
              
         }
         echo '<header id="top-username" onclick="profileClick('.$idUser.')">'.$username.'</header>';
       ?>
       <nav>
       <ul class = "user-dropDownMenu">
			<li>
				<img src="css/images&shit/dropDownArrow.png" class = "dropDownArrow">
				<ul class="dropDownMenu">
					<li onclick="userSettings()">settings</li>
					<li onclick="logout()">logout</li>
				</ul>
			</li>
		</ul> 
       </nav>
   </div>
</div>
 <div class = "container settings">
  <div class = "settingsHeader-wrapper">
    <label>Settings</label>
  </div>
  <div class = "settings-wrapper">
    <div class = "changeUsername-wrapper">
      <label>Username: <?php echo $username ?></label>      
    </div class = "changePassword-wrapper">
    <div>
      <label>Change Password</label>      
    </div>
  </div>
   
 </div>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="js/mainPage.js"></script>

</body>
</html>