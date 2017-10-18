<?php
function login($user, $pass){
	$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
	$stmt = $db->prepare("SELECT * FROM users WHERE username=? AND password=?");
	$stmt->bindParam(1,$user);
	$stmt->bindParam(2,$pass);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$username = $row['username'];
  $password = $row['password'];
  $id = $row['userid'];
  $type = $row['type'];
  if($username == $user && $password == $pass) {
  	session_start();
    $_SESSION['username'] = $user;
    $_SESSION['password'] = $pass;
    $_SESSION['id'] = $id;
    $_SESSION['type'] = $type;
    $_SESSION['log'] = 0;
  	return true;
  }
  else{
  	return false;
  }
}
function addUser($fname,$lname,$user,$pass,$type){
	$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
	$stmt = $db->prepare("SELECT * FROM users WHERE username=?");
	$stmt->bindParam(1,$user);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if($row>0){
		return false;
	}
	else{
		$stmt = $db->prepare("INSERT INTO users (fname,lname,username,password,type) VALUES(?,?,?,?,?)");
		$stmt->bindParam(1,$fname);
		$stmt->bindParam(2,$lname);
		$stmt->bindParam(3,$user);
		$stmt->bindParam(4,$pass);
		$stmt->bindParam(5,$type);
		$stmt->execute();
		return true;
	}
}
function deleteUser($username){
	$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
	$stmt = $db->prepare("SELECT * FROM users WHERE username=?");
	$stmt->bindparam(1,$username);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if($row>0){
		$stmt = $db->prepare("DELETE FROM users WHERE username=?");
		$stmt->bindParam(1,$username);
		$stmt->execute();
		return true;
	}
	else{
		return false;
	}
}
function addProduct($prodname,$prodprice,$prodquan,$proddesc,$prodsupp, $prod_img){
	$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
	$stmt = $db->prepare("SELECT * FROM stock WHERE prod_name=?");
	$stmt->bindParam(1,$prodname);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if($row>0){
		return false;
	}
	else{
		$stmt = $db->prepare("INSERT INTO stock (prod_name,prod_price,prod_quan,prod_desc,prod_supplier,prod_img) VALUES(?,?,?,?,?,?)");
		$stmt->bindParam(1,$prodname);
		$stmt->bindParam(2,$prodprice);
		$stmt->bindParam(3,$prodquan);
		$stmt->bindParam(4,$proddesc);
		$stmt->bindParam(5,$prodsupp);
		$stmt->bindParam(6,$prod_img);
		$stmt->execute();
		return true;
	}
}
function upload($img){
	$targetdir = "products/";
	$targetfile = $targetdir . basename($img).".jpg";
	$uploadOk = 1;
	$check = getimagesize($img);
	move_uploaded_file($img, $targetfile);
}
function search($prodname){
	$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
	$stmt = $db->prepare("SELECT * FROM stock WHERE prod_name=? OR prod_id=?");
	$stmt->bindParam(1,$prodname);
	$stmt->bindParam(2,$prodname);
	$stmt->execute();
	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if($row>0){
		return $row;
	}
	else{
		return false;
	}
}
function inven($prodname){
	$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
	$stmt = $db->prepare("SELECT * FROM stock WHERE prod_name OR prod_desc LIKE ?");
	$stmt->bindValue(1,"%$prodname%", PDO::PARAM_STR);
	$stmt->execute();
	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if($row>0){
		return $row;
	}
	else{
		return false;
	}
}
function updatestocks($prodid,$prodstocks){
	$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
	$stmt = $db->prepare("SELECT * FROM stock WHERE id=?");
	$stmt->bindParam(1,$prodid);
	$stmt->execute();
	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if($row>0){
		$stmt = $db->prepare("UPDATE stock SET prod_quan=? WHERE prod_id=?");
		$stmt->bindParam(1,$prodstocks);
		$stmt->bindParam(2,$prodid);
		$stmt->execute();
		return true;
	}
	else{
		return false;
	}
}

?>