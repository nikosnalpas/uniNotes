<?php 
session_start();
require 'db.php';
require 'getProfileInfo.php';
require 'getUserProfileInfo.php';
if ( $_SESSION['logged_in'] != true ) {
  header("location: index.php");    
}
else {
    //mainUser
    $idUser = $_SESSION['idUser'];
    $username = $_SESSION['username'];
    $profPic = $_SESSION['profPic'];
     $profPicType = $_SESSION['profPicType'];
    //clickedUser
    $idClicked = $_SESSION['idClicked'];
    $userProfPic = $_SESSION['userProfPic'];
    $userProfPicType = $_SESSION['userProfPicType'];
    $userProfileName = $_SESSION['userProfileUsername'];
    $uniName = $_SESSION['userProfileUni'];
    $depName = $_SESSION['userProfileDep'];
    $idClasses = $_SESSION['userProfileClasses'];
     $idClass = $_SESSION['classClicked'];

    
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Unuo</title>
      <link rel="stylesheet" href="css/mainStyle.css">
</head>

<body>


  <?php
  $dbh = new PDO("mysql:host=localhost;dbname=uninotes_unuo", "uninotes_nikos", "Nikos@3145");
  if(isset($_POST['btn'])){
    $name=$_FILES['myfile']['name'];
    $type = $_FILES['myfile']['type'];
    $data = file_get_contents($_FILES['myfile']['tmp_name']);
    $file = $_FILES['myfile']['tmp_name'];
    $fileExt = explode('.', $name);
    $fileActualExt = strtolower(end($fileExt));
    $allowed = array('jpg', 'jpeg', 'png', 'pdf');
    if(in_array($fileActualExt, $allowed)){
 
        if($size<100000000){
          $fileNameNew = uniqid('' , true).".".$fileActualExt;          
           $fileDestination = 'profilePictures/thumbnails/'.$fileNameNew;
         // move_uploaded_file($_FILES['myfile']['tmp_name'], $fileDestination);
          require 'phpFunctions/imageResize.php';
          if(smart_resize_image($file, $data, 70,70, true, $fileDestination, false, false, 100)==true){
             $fileDestination2 = 'profilePictures/mediumView/'.$fileNameNew;
             if(smart_resize_image($file, $data, 350,350, true, $fileDestination2, false, false, 100)==true){
              $fileDestination3 = 'profilePictures/'.$fileNameNew;
                move_uploaded_file($_FILES['myfile']['tmp_name'], $fileDestination3);
             }
          }
         





        }else{
          echo "your file is too big";
        }

    }else{
      echo "You cannot upload files of this type!";
    }




    $stmt = $dbh->prepare("UPDATE users SET profPic = ? , profPicType = ? WHERE idusers='$idUser'");
    $stmt->bindParam(1,$fileNameNew);
    $stmt->bindParam(2,$type);
    $stmt->execute();
    echo '';

 

  }




  ?>



  <img src="css/images&shit/profBack.jpg" class="profBack">
  <div class="profPicContainer">
      <form method="post" enctype="multipart/form-data">
    <input name="myfile" type="file"/ id="imageUpload" onchange ="uploadPic(this);">
    <button name="btn" id="confirmUpload" >Confirm</button>
  
      <?php 
       if($idClicked != $idUser){
          echo '<label class = "messageBtn" onclick = "openMessage('.$idClicked.')">Message</label>';
        }
         if($_SESSION['profPic']=='0' and $idUser == $idClicked){
              echo '<img src="css/images&shit/user.png" class="profPic" onmouseover="profPicHover()" onmouseleave="profPicOut()">';
              if($idUser == $idClicked)
              echo '<img src="css/images&shit/upload_circle.png" name="upPic" id="upload_circle">';  
         }else{

               echo '<img src="profilePictures/mediumView/'.$userProfPic.'" class = "profPic" onmouseover="profPicHover()" onmouseleave="profPicOut()"/>';
                if($idUser == $idClicked)
               echo '<img src="css/images&shit/upload_circle.png" name="upPic" id="upload_circle">';  
              
         }
         

         
       ?>
       </form>
      
      
      
      
             <label><?php echo $userProfileName ?></label>  


  </div>
  
   


  <div class = "profile-container">
      <div id="courses-wrapper profile">
    <div class = "coursesHeader-wrapper profile">
    <header id="CoursesHeader">Courses</header>
      </div>
        <div class = "elements profile">      
    <?php
    
    if($idClass == 0){
       echo'<div id="0" class = "coursesElements active" onclick = "courseClick(this.id);"><header class = "courseElement">All</header></div>';
    }else{
       echo'<div id="0" class = "coursesElements" onclick = "courseClick(this.id);"><header class = "courseElement">All</header></div>';
    }
        foreach($idClasses as $classId){
          $id = $classId['Classes_idClasses'];
          $result = $mysqli->query("SELECT className FROM classes WHERE idClasses = '$id'");
          foreach($result as $className){
            if($idClass == $id){
              echo'<div id='.$id.' class = "coursesElements active" onclick = "courseClick(this.id);"><header class = "courseElement">'.$className['className'] . '</header></div>';
            }else{
              echo'<div id='.$id.' class = "coursesElements" onclick = "courseClick(this.id);"><header class = "courseElement">'.$className['className'] . '</header></div>';
            }
            
          }
        }
        
    ?>
    
  </div>
</div>


<ul class = "mainList" id = "mainList">    
    <?php       
            if(is_null($idClass)){
              $idClass==0;
            }     
            if($idClass==0){ 
               $result = $mysqli->query("SELECT * FROM notes WHERE users_idusers = '$idClicked' ORDER BY addedAt DESC");
            }else{
               $result = $mysqli->query("SELECT * FROM notes WHERE users_idusers = '$idClicked' AND Classes_idClasses = $idClass ORDER BY addedAt DESC");
            }      
        foreach($result as $noteArray){         
          $id = $noteArray['users_idusers'];
          $idNotes = $noteArray['idNotes'];
          $user = $mysqli->query("SELECT idusers,username,profPic,profPicType FROM users WHERE idusers = '$id'");      
          foreach($user as $u){
              echo '<div class="container photo-wrapper-top" id = "container-photo-wrapper'.$idNotes.'">';
              if(is_null($u['profPic']))
              echo '<img src="css/images&shit/user.png" onclick="profileClick('.$id.')" class="userPic">';
              else
              echo '<img src="data:'.$u['profPicType'].';base64,'.base64_encode($u['profPic']).'" onclick="profileClick('.$id.')" class="userPic">';
              echo '<header onclick="profileClick('.$id.')" id = "postUsername">'. $u['username'] .'</header><br>'               
              .'<header id = "postDate">'. $noteArray['addedAt'] .'</header><br>'                           
                .'</div>'
                .'<div class="dots" id = "dots'.$idNotes.'"  onclick="showDropDownNotes('.$idNotes.')">'  
                  .' <img src="css/images&shit/3dots.png" class="noteMore">'          
                    .'<ul class="dropDownNote" id = "dropDownNote'.$idNotes.'">'
             .'<li onclick = "readLaterPost('.$idNotes.')">read later</li>';
             if($id == $idUser)
              echo '<li onclick = "deletePost('.$idNotes.')">delete</li>';
            echo '</ul>'
            .'</div>';
                    echo '<img src="noteImages/'.$noteArray['image'].'" class = "askisi" id="askisi'.$idNotes.'"/>'
                    .'<div class = "imageAbout-wrapper" id = "imageAbout-wrapper'.$idNotes.'">';
                   
                    if(preg_match("/[a-z]/i", $noteArray['imageAbout'])){
                    echo '<header id="usernameAbout">'.$u['username'].': </header>'
                    .'<p id = "imageAboutText">'.$noteArray['imageAbout'].'</p>';              
                   }                   
                   echo '</div>';                  
                   $upvoteResult = $mysqli->query("SELECT * FROM note_has_upvote WHERE notes_idnotes='$idNotes' AND users_idusers = $idUser");
                   $downvoteResult = $mysqli->query("SELECT * FROM note_has_downvote WHERE notes_idnotes='$idNotes' AND users_idusers = $idUser");
                   if($upvoteResult->num_rows>0){
                      echo '<div class = "votesComments-wrapper up" id="vcw'.$idNotes.'">'                                                                         
                   .'<img src="css/images&shit/upvote.png" style="display: none;" class="upvote" id="upvote'.$idNotes.'" onclick="upvote('.$idNotes.' , this.className)">'
                   .'<img src="css/images&shit/upvoteClicked.png" class="upvoteClicked" id="upvoteClicked'.$noteArray['idNotes'].'" onclick="upvote('.$idNotes.' , this.className)">'
                   .'<header class = "pointsHeader" id="points'.$idNotes.'">'.$noteArray['points'].'</header>'
                   .'<img src="css/images&shit/downvote.png" class="downvote" id="downvote'.$idNotes.'" onclick="downvote('.$noteArray['idNotes'].' , this.className)">' 
                   .'<img src="css/images&shit/downvoteClicked.png" style="display: none;" class="downvoteClicked" id="downvoteClicked'.$idNotes.'" onclick="downvote('.$idNotes.' , this.className)">'                  
                   .'</div>';
                   }else if($downvoteResult->num_rows>0){
                      echo '<div class = "votesComments-wrapper down" id="vcw'.$idNotes.'">'                                                                         
                   .'<img src="css/images&shit/upvote.png" class="upvote" id="upvote'.$idNotes.'" onclick="upvote('.$idNotes.' , this.className)">'
                   .'<img src="css/images&shit/upvoteClicked.png" style="display: none;" class="upvoteClicked" id="upvoteClicked'.$noteArray['idNotes'].'" onclick="upvote('.$idNotes.' , this.className)">'
                   .'<header class = "pointsHeader" id="points'.$idNotes.'">'.$noteArray['points'].'</header>'
                   .'<img src="css/images&shit/downvote.png" style="display: none;" class="downvote" id="downvote'.$idNotes.'" onclick="downvote('.$noteArray['idNotes'].' , this.className)">' 
                   .'<img src="css/images&shit/downvoteClicked.png" class="downvoteClicked" id="downvoteClicked'.$idNotes.'" onclick="downvote('.$idNotes.' , this.className)">'

                   .'</div>';
                   }else{
                    echo '<div class = "votesComments-wrapper" id="vcw'.$idNotes.'">'                                                                         
                   .'<img src="css/images&shit/upvote.png" class="upvote" id="upvote'.$idNotes.'" onclick="upvote('.$idNotes.' , this.className)">'
                   .'<img src="css/images&shit/upvoteClicked.png" style="display: none;" class="upvoteClicked" id="upvoteClicked'.$noteArray['idNotes'].'" onclick="upvote('.$idNotes.' , this.className)">'
                   .'<header class = "pointsHeader" id="points'.$idNotes.'">'.$noteArray['points'].'</header>'
                   .'<img src="css/images&shit/downvote.png" class="downvote" id="downvote'.$idNotes.'" onclick="downvote('.$noteArray['idNotes'].' , this.className)">' 
                   .'<img src="css/images&shit/downvoteClicked.png" style="display: none;" class="downvoteClicked" id="downvoteClicked'.$idNotes.'" onclick="downvote('.$idNotes.' , this.className)">'                  
                   .'</div>';
                   }                   
                   $commnentsArray = $mysqli->query("SELECT * FROM note_has_comments WHERE notes_idnotes = '$idNotes'");
                   echo '<div class="commentsButton" id = "commentsButton'.$idNotes.'" onclick="commentsOpen('.$idNotes.')"><header class="comments">comments:'.$commnentsArray->num_rows.'</header></div>';  
                   echo '<div class = "commentElements" id="commentElements'.$idNotes.'">';                       
                       //get all comments                       
                       echo '<div class = "justComments" id = "justComments'.$idNotes.'">';
                       if($commnentsArray->num_rows>0){                      
                       foreach($commnentsArray as $c){                        
                        //get user name that made the comment
                        $idUserComment = $c['users_idusers'];
                        $commentUser = $mysqli->query("SELECT username FROM users WHERE idusers = '$idUserComment'");

                        foreach($commentUser as $unC){
                          $usernameComment = $unC['username'];
                        }
                        echo '<div id="commentElement">'
                       .'<header class="commentUser">'.$usernameComment.'</header>'
                       .'<header class="comment">'.$c['comment'].'</header>' 
                       .'</div>';                       
                       }
                     }
                  echo'</div>'
                  .'<input type="text" placeholder="Comment here" autocomplete="off" class = "commentInput" id="commentInput'.$idNotes.'"/>'
                  .'<button class="postComment" onclick="postComment('.$idNotes.')" id="postComment'.$idNotes.'">post</button>'
                  .'</div>';                                                            
                  }                  
        }
        ?> 
    </ul>
    </div>

 <div id="top-wrapper">
      <img src="css/images&shit/logo.png" class="logo" onclick="mainLogoClick()">
      <input type="text" autocomplete="off" placeholder = "Search Courses" oninput="showAddCourse()" list = "coursesInList" id="coursesList">
          <datalist id="coursesInList">
            <?php           
            $coursesArray = $mysqli->query("SELECT * FROM classes WHERE Departments_idDepartments='$idDepartment'");                
            foreach($coursesArray as $course){
              echo '<option value = "' . $course['className'] . '">';             
            }
            echo '<option value = "asdasdasd">';      
            ?>  
          </datalist> 
      <button id = "addCourse" onclick = "addCourse()" class = "addCourse">add</button>               
      <div class = "top-container">
      <div class = "locationButtons">        
        <div class = "mainPageButtonWrapper">
          <label class = "mainPageLabel" onclick = "mainPage()">maing page</label>
        </div>
        <div class = "readLaterButtonWrapper">
          <label class = "readLaterLabel" onclick= "readLaterRedirect()">read later</label>
        </div>
        <div class = "msgIcon_container" onclick="unReadMessagesOpen()">
          <img src="css/images&shit/msgIcon.png" class = "msgIcon">
        </div>
        <div id = "msgNotifications_container">
         <?php         
          $result = $mysqli->query("SELECT opened FROM messagedusers WHERE users_idusers = '$idUser' AND opened = 0");
          if($result->num_rows>0){
              echo '<div class = "msgNotifications_container" onclick="unReadMessagesOpen()">'
              .'<label>'.$result->num_rows.'</label>'
              .'</div>';
          }
          ?>
        </div>
        <div class = "unReadMessages_container" id = "unReadMessages_container">
        </div>
      </div>
      <div class = "username-profPic-wrapper">
      <?php
      if($_SESSION['profPic']=='0'){
              echo '<img src="css/images&shit/user.png" id="top-profPic" onclick="profileClick('.$idUser.')">';
         }else{

               echo '<img src="profilePictures/thumbnails/'.$profPic.'" id="top-profPic" onclick="profileClick('.$idUser.')"/>'; 
              
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




    
      <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
      <script src="js/mainPage.js"></script>
    <script src="js/profile.js"></script>


</body>
</html>