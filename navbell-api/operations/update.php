<?php
/*
coded by m.slamat
*/
// propreites & parametres  initialisation 

$glob->fname = $fname;
$glob->lname = $lname;
if ($ispublic==true) {
	$ispublic = 1;
}elseif ($ispublic==false) {
	$ispublic = 0;
}
$glob->ispublic = $ispublic;
$glob->id=$id;
//$glob->salt = "$".substr(base64_encode(md5(microtime())), 30)."$"; // random salt 

// crypting user password 
$glob->password = cryptpwd($password,$glob->salt);
// storing user picture data in our server (return url) 
$glob->picture = upimg($picture);
if($glob->updateuser()){
	$mouh["reponse"]=1;	
	$mouh["picture"]=$glob->picture;
}else{
	$mouh["reponse"]=-1;
}
die(json_encode($mouh));
?>