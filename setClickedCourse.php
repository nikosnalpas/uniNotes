<?php
session_start();
require 'db.php';
$classClicked = $_REQUEST['q'];
$_SESSION['classClicked'] = $classClicked;
$idUser = $_SESSION['idUser'];

if($classClicked==0){ 
    $result = $mysqli->query("SELECT * FROM notes ORDER BY addedAt DESC");
}else{
    $result = $mysqli->query("SELECT * FROM notes WHERE Classes_idClasses = $classClicked ORDER BY addedAt DESC");
}     
$options = '';
        foreach($result as $noteArray){         
          $id = $noteArray['users_idusers'];
          $idNotes = $noteArray['idNotes'];
          $user = $mysqli->query("SELECT idusers,username,profPic,profPicType FROM users WHERE idusers = '$id'");      
          foreach($user as $u){
              $options = $options. '<div class="container photo-wrapper-top" id = "container-photo-wrapper'.$idNotes.'">';
              if(is_null($u['profPic']))
              $options = $options. '<img src="css/images&shit/user.png" onclick="profileClick(this.id)" class="userPic">';
              else
              $options = $options. '<img src="profilePictures/thumbnails/'.$u['profPic'].'" onclick="profileClick(this.id)" class="userPic">';
              $options = $options. '<header onclick="profileClick(this.id)" id = "postUsername">'. $u['username'] .'</header><br>'               
              .'<header id = "postDate">'. $noteArray['addedAt'] .'</header><br>'                           
                .'</div>'
                .'<div class="dots" id = "dots'.$idNotes.'"  onclick="showDropDownNotes('.$idNotes.')">'  
                  .' <img src="css/images&shit/3dots.png" class="noteMore">'          
                    .'<ul class="dropDownNote" id = "dropDownNote'.$idNotes.'">'
             .'<li onclick = "readLaterPost('.$idNotes.')">read later</li>';
             if($id == $idUser)
              $options = $options . '<li onclick = "deletePost('.$idNotes.')">delete</li>';
              else
              $options = $options . '<li onclick = "openMessage('.$id.')">message</li>';
            $options = $options . '</ul>'
            .'</div>';
                    $options = $options. '<img src="noteImages/mediumView/'.$noteArray['image'].'" class = "askisi" id="askisi'.$idNotes.'"/>'
                    .'<div class = "imageAbout-wrapper" id = "imageAbout-wrapper'.$idNotes.'">';
                   
                    if(preg_match("/[a-z]/i", $noteArray['imageAbout'])){
                    $options = $options. '<header id="usernameAbout">'.$u['username'].': </header>'
                    .'<p id = "imageAboutText">'.$noteArray['imageAbout'].'</p>';              
                   }                   
                   $options = $options. '</div>';                  
                   $upvoteResult = $mysqli->query("SELECT * FROM note_has_upvote WHERE notes_idnotes='$idNotes' AND users_idusers = $idUser");
                   $downvoteResult = $mysqli->query("SELECT * FROM note_has_downvote WHERE notes_idnotes='$idNotes' AND users_idusers = $idUser");
                   if($upvoteResult->num_rows>0){
                      $options = $options. '<div class = "votesComments-wrapper up" id="vcw'.$idNotes.'">'                                                                         
                   .'<img src="css/images&shit/upvote.png" style="display: none;" class="upvote" id="upvote'.$idNotes.'" onclick="upvote('.$idNotes.' , this.className)">'
                   .'<img src="css/images&shit/upvoteClicked.png" class="upvoteClicked" id="upvoteClicked'.$noteArray['idNotes'].'" onclick="upvote('.$idNotes.' , this.className)">'
                   .'<header class = "pointsHeader" id="points'.$idNotes.'">'.$noteArray['points'].'</header>'
                   .'<img src="css/images&shit/downvote.png" class="downvote" id="downvote'.$idNotes.'" onclick="downvote('.$noteArray['idNotes'].' , this.className)">' 
                   .'<img src="css/images&shit/downvoteClicked.png" style="display: none;" class="downvoteClicked" id="downvoteClicked'.$idNotes.'" onclick="downvote('.$idNotes.' , this.className)">'                  
                   .'</div>';
                   }else if($downvoteResult->num_rows>0){
                      $options = $options. '<div class = "votesComments-wrapper down" id="vcw'.$idNotes.'">'                                                                         
                   .'<img src="css/images&shit/upvote.png" class="upvote" id="upvote'.$idNotes.'" onclick="upvote('.$idNotes.' , this.className)">'
                   .'<img src="css/images&shit/upvoteClicked.png" style="display: none;" class="upvoteClicked" id="upvoteClicked'.$noteArray['idNotes'].'" onclick="upvote('.$idNotes.' , this.className)">'
                   .'<header class = "pointsHeader" id="points'.$idNotes.'">'.$noteArray['points'].'</header>'
                   .'<img src="css/images&shit/downvote.png" style="display: none;" class="downvote" id="downvote'.$idNotes.'" onclick="downvote('.$noteArray['idNotes'].' , this.className)">' 
                   .'<img src="css/images&shit/downvoteClicked.png" class="downvoteClicked" id="downvoteClicked'.$idNotes.'" onclick="downvote('.$idNotes.' , this.className)">'

                   .'</div>';
                   }else{
                    $options = $options. '<div class = "votesComments-wrapper" id="vcw'.$idNotes.'">'                                                                         
                   .'<img src="css/images&shit/upvote.png" class="upvote" id="upvote'.$idNotes.'" onclick="upvote('.$idNotes.' , this.className)">'
                   .'<img src="css/images&shit/upvoteClicked.png" style="display: none;" class="upvoteClicked" id="upvoteClicked'.$noteArray['idNotes'].'" onclick="upvote('.$idNotes.' , this.className)">'
                   .'<header class = "pointsHeader" id="points'.$idNotes.'">'.$noteArray['points'].'</header>'
                   .'<img src="css/images&shit/downvote.png" class="downvote" id="downvote'.$idNotes.'" onclick="downvote('.$noteArray['idNotes'].' , this.className)">' 
                   .'<img src="css/images&shit/downvoteClicked.png" style="display: none;" class="downvoteClicked" id="downvoteClicked'.$idNotes.'" onclick="downvote('.$idNotes.' , this.className)">'                  
                   .'</div>';
                   }                   
                   $commnentsArray = $mysqli->query("SELECT * FROM note_has_comments WHERE notes_idnotes = '$idNotes'");
                   $options = $options. '<div class="commentsButton" id = "commentsButton'.$idNotes.'" onclick="commentsOpen('.$idNotes.')"><header class="comments">comments:'.$commnentsArray->num_rows.'</header></div>';  
                   $options = $options. '<div class = "commentElements" id="commentElements'.$idNotes.'">';                       
                       //get all comments                       
                       $options = $options. '<div class = "justComments" id = "justComments'.$idNotes.'">';
                       if($commnentsArray->num_rows>0){                      
                       foreach($commnentsArray as $c){                        
                        //get user name that made the comment
                        $idUserComment = $c['users_idusers'];
                        $commentUser = $mysqli->query("SELECT username FROM users WHERE idusers = '$idUserComment'");

                        foreach($commentUser as $unC){
                          $usernameComment = $unC['username'];
                        }
                        $options = $options. '<div id="commentElement">'
                       .'<header class="commentUser">'.$usernameComment.'</header>'
                       .'<header class="comment">'.$c['comment'].'</header>' 
                       .'</div>';                       
                       }
                     }
                  $options = $options.'</div>'
                  .'<input type="text" placeholder="Comment here" autocomplete="off" class = "commentInput" id="commentInput'.$idNotes.'"/>'
                  .'<button class="postComment" onclick="postComment('.$idNotes.')" id="postComment'.$idNotes.'">post</button>'
                  .'</div>';                                                            
                  }                  
        }
        echo $options;