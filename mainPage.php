<?php
session_start();
if ( $_SESSION['logged_in'] != true ) {
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
    $_SESSION['refresh'] = false;
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
     <ul class = "mainList" id = "mainList">    
   <?php       
  $dbh = new PDO("mysql:host=localhost;dbname=uninotes_unuo", "uninotes_nikos", "Nikos@3145");
  if(isset($_POST['btn'])){
    $idClass = $_SESSION['uploadToId'];
    $name=$_FILES['myfile']['name'];
    $postFileType = $_SESSION['postFileType'];
    if($postFileType=='image'){
       $type = $_FILES['myfile']['type'];
       $data = file_get_contents($_FILES['myfile']['tmp_name']);
       $file = $_FILES['myfile']['tmp_name'];
       $fileExt = explode('.', $name);
       $fileActualExt = strtolower(end($fileExt));
       $allowed = array('jpg', 'jpeg', 'png', 'pdf');
       if(in_array($fileActualExt, $allowed)){
 
            if($size<100000000){
             $fileNameNew = uniqid('' , true).".".$fileActualExt;          
              $fileDestination = 'noteImages/mediumView/'.$fileNameNew;
            // move_uploaded_file($_FILES['myfile']['tmp_name'], $fileDestination);
             require 'phpFunctions/imageResize.php';
             if(smart_resize_image($file, $data, 550,550, true, $fileDestination, false, false, 100)==true){
                $fileDestination3 = 'noteImages/'.$fileNameNew;
                      move_uploaded_file($_FILES['myfile']['tmp_name'], $fileDestination3);
                   echo '';
                   //header("Location:http://www.uninotes.a2hosted.com/mainPage.php");
             }
         





           }else{
             echo "your file is too big";
           }

       }else{
         echo "You cannot upload files of this type!";
       }


       $imageAbout = htmlspecialchars($_POST['textAbout']);
       $stmt = $dbh->prepare("INSERT INTO notes (Departments_idDepartments,    Universities_idUniversities,Classes_idClasses,users_idusers,image,imageType,imageAbout) VALUES (?,?,?,?,?,?,?)");
        $stmt->bindParam(1,$idDep);
       $stmt->bindParam(2,$idUni);
       $stmt->bindParam(3,$idClass);
       $stmt->bindParam(4,$idUser);
       $stmt->bindParam(5,$fileNameNew);
       $stmt->bindParam(6,$type);
       $stmt->bindParam(7,$imageAbout);
       $stmt->execute();
  }else{
     //upload only text
       $imageAbout =  htmlspecialchars($_POST['textAbout']);
       $type = 'text';
       $data = '';
       $stmt = $dbh->prepare("INSERT INTO notes (Departments_idDepartments,    Universities_idUniversities,Classes_idClasses,users_idusers,image,imageType,imageAbout) VALUES (?,?,?,?,?,?,?)");
        $stmt->bindParam(1,$idDep);
       $stmt->bindParam(2,$idUni);
       $stmt->bindParam(3,$idClass);
       $stmt->bindParam(4,$idUser);
       $stmt->bindParam(5,$data);
       $stmt->bindParam(6,$type);
       $stmt->bindParam(7,$imageAbout);
       $stmt->execute();
  }

  }
            if(is_null($idClass)){
              $idClass==0;
            }     
            if($idClass==0){ 
               $result = $mysqli->query("SELECT * FROM notes ORDER BY addedAt DESC");
            }else{
               $result = $mysqli->query("SELECT * FROM notes WHERE Classes_idClasses = $idClass ORDER BY addedAt DESC");
            }      
        foreach($result as $noteArray){         
          $id = $noteArray['users_idusers'];
          $idNotes = $noteArray['idNotes'];
          $user = $mysqli->query("SELECT idusers,username,profPic,profPicType FROM users WHERE idusers = '$id'");      
          foreach($user as $u){
              echo '<div class="container photo-wrapper-top '.$noteArray['imageType'].'" id = "container-photo-wrapper'.$idNotes.'">';
              if(is_null($u['profPic']))
              echo '<img src="css/images&shit/user.png" onclick="profileClick('.$id.')" class="userPic">';
              else
              echo '<img src="profilePictures/thumbnails/'.$u["profPic"].'" onclick="profileClick('.$id.')" class="userPic">';
              echo '<header onclick="profileClick('.$id.')" id = "postUsername">'. $u['username'] .'</header><br>'               
              .'<header id = "postDate">'. $noteArray['addedAt'] .'</header><br>'                           
                .'</div>'
                .'<div class="dots" id = "dots'.$idNotes.'"  onclick="showDropDownNotes('.$idNotes.')">'  
                  .' <img src="css/images&shit/3dots.png" class="noteMore">'           
                    .'<ul class="dropDownNote" id = "dropDownNote'.$idNotes.'">'
             .'<li onclick = "readLaterPost('.$idNotes.')">read later</li>';
             if($id == $idUser)
              echo '<li onclick = "deletePost('.$idNotes.')">delete</li>';
              else
              echo '<li onclick = "openMessage('.$id.')">message</li>';
            echo '</ul>'
            .'</div>';
                      if($noteArray['imageType']=='text'){
                      echo '<div id = "askisiTypeText'.$idNotes.'" class = "askisiTypeText"><label>'.$noteArray['imageAbout'].'</label></div>'
                      .'<div class = "imageAbout-wrapper" id = "imageAbout-wrapper'.$idNotes.'"></div>';

                    }else{
                      echo '<img src="noteImages/mediumView/'.$noteArray['image'].'" class = "askisi" id="askisi'.$idNotes.'" onclick = "askisiFullOpen('.$idNotes.')"/>';

                    echo '<div class = "imageAbout-wrapper" id = "imageAbout-wrapper'.$idNotes.'">';
                   
                    if(preg_match("/[a-z]/i", $noteArray['imageAbout'])){
                    echo '<header id="usernameAbout">'.$u['username'].': </header>'
                    .'<p id = "imageAboutText">'.$noteArray['imageAbout'].'</p>';              
                   }                   
                   echo '</div>';
                   }                                                   
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
  <div class = "mainMessages_container" id = "mainMessages_container">
    <?php
  $messagedUsers = $mysqli->query("SELECT idmessagedUsers FROM messagedusers WHERE users_idusers = '$idUser'");
  foreach($messagedUsers as $m){
    $idMu = $m['idmessagedUsers'];
    $messagedUser = $mysqli->query("SELECT username,profPic,profPicType FROM users WHERE idusers = '$idMu'");
    foreach($messagedUser as $mu){
      echo ' <div class = "person_right_container" id="person_right_container'.$idMu.'" onclick = "person_right_click('.$idMu.')">';
      if(is_null($mu['profPic'])){
        echo '<img src="css/images&shit/user.png"  class = "img_person_right">';
      }else{
        echo '<img src="profilePictures/thumbnails/'.($mu['profPic']).'"  class = "img_person_right">';
      }   
        echo '<div class = "name_container">'
           .'<label>'.$mu['username'].'</label>'
       .'</div>';
        $result = $mysqli->query("SELECT active FROM users WHERE idusers = '$idMu'");
        foreach ($result as $res) {
          $active = $res['active'];
        }
        if($active)
       echo '<img src = "css/images&shit/activeDot.png" class = "activeDot">';
    echo '</div>';

    }

  }

  ?>
</div>
<div class = "messageTabs_container" id = "messageTabs_container">
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
          <label class = "mainPageLabel active" onclick = "mainPage()">main page</label>
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


 <div class="courses-wrapper">
    <div class = "coursesHeader-wrapper">
    <header id="CoursesHeader">Courses</header>
      </div>
        <div class = "elements" id="elements">      
    <?php
    $idTemp = 0;
    if($idClass == 0){
       echo'<div id="course'.$idTemp.'" class = "coursesElements active" onclick = "courseClick('.$idTemp.')"><header class = "courseElement">All</header></div>';
    }else{
       echo'<div id="course'.$idTemp.'" class = "coursesElements" onclick = "courseClick('.$idTemp.')"><header class = "courseElement">All</header></div>';
    }
        foreach($idClasses as $classId){
          $id = $classId['Classes_idClasses'];
          $result = $mysqli->query("SELECT className FROM classes WHERE idClasses = '$id'");
          foreach($result as $className){
            if($idClass == $id){
              echo'<div id= "course'.$id.'" class = "coursesElements active"><header onclick = "courseClick('.$id.')" class = "courseElement">'.$className['className'] . '</header><img src="css/images&shit/dropDownArrow.png" class="plus" id = "plus'.$id .'" onclick="revealCoursesDropDown('.$id.')"></div>';
            }else{
              echo'<div id= "course'.$id.'" class = "coursesElements"><header onclick = "courseClick('.$id.')" class = "courseElement">'.$className['className'] . '</header><img src="css/images&shit/dropDownArrow.png" class="plus" id = "plus'.$id .'" onclick="revealCoursesDropDown('.$id.')"></div>';
            } 
            echo '<ul class = "uploadToCourse-wrapper" id= "uploadToCourse-wrapper'.$id.'">'
            .'<li onclick = "uploadToCourse('.$id.')">Upload</li>'   
            .'<li onclick = "unfollowCourse('.$id.')">Unfollow</li>'          
            .'</ul>';           
          }
        }
    ?> 
    
  </div>


      <img src="css/images&shit/black.jpg" id="black" onclick="cancelUpload()">
      

  <form method="post" enctype="multipart/form-data" id="upload-wrapper">  
    <input name="myfile" type="file"/ id="imageUpload" onchange ="uploadPic(this);">
    <img src = "css/images&shit/uploadImage.png" id="picUploadThumbnail" onclick="imageUploadIconClick()">
    <?php
      if($_SESSION['profPic']=='0'){
              echo '<img src="css/images&shit/user.png" id="profPicAbout" onclick="userProfile()">';
         }else{
               echo '<img src="profilePictures/thumbnails/'.$profPic.'" id="profPicAbout" onclick="userProfile()"/>'; 
         }
       ?>
    <textarea maxlength="100" placeholder="Describe your post." name = "textAbout" id="textAbout"></textarea>
    <button name="btn" id="upload" >Upload</button>
    <button id="cancelUpload" onclick="cancelUpload()">Cancel</button>
</form>
</div>
<div class = "readLaterAdded-wrapper">
  <label>Added to read later!</label>
  <img src="css/images&shit/checkIcon.png" class = "checkIcon">
</div>
<div class = "CourseAdded-wrapper">
  <label>Course added to your following!</label>
  <img src="css/images&shit/checkIcon.png" class = "checkIcon">
</div>
<div class = "courseDoesNotExist-wrapper">
  <label>Course does not exist! Select from the list</label>
</div>
<div class = "UnfollowedPopUp-wrapper">
  <label>Unfollowed!</label>
  <img src="css/images&shit/checkIcon.png" class = "checkIcon">
</div>
<div class = "askisFull_container" id="askisFull_container" onclick="askisiFullClose()">
  <div class = "askisiFullCenter_container">  
    <div class = "askisiFullImg_container">
      <img src="css/images&shit/user.png"> 
      <div class="askisiFullRight_container">
        <div class = "askisiFullRightTop_container">
           <label>Nikos Nalpas</label>
        </div>
         <label>test</label>
      </div> 
    </div> 
  </div>

</div>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="js/mainPage.js"></script>

</body>
</html>