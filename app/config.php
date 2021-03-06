<?php
class Funcs{
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
	    $_SESSION['pay'] = false;
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
	function loadShop(){
		$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
		$stmt = $db->prepare("SELECT * FROM stock WHERE prod_quan>0");
		$stmt->execute();
		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if($row>0){
			return $row;
		}
		else{
			return false;
		}
	}
	function addtoCart($prodid,$userid,$price){
		$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
		$stat = "unpaid";
		$quan = 1;
		$stmt = $db->prepare("SELECT * FROM cart WHERE prod_id=? AND user_id=?AND status='unpaid'");
		$stmt->bindParam(1,$prodid);
		$stmt->bindParam(2,$userid);
		$stmt->execute();
		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if($row==NULL){
			$stmt = $db->prepare("INSERT INTO cart (user_id,prod_id,item_quan,total,status) VALUES(?,?,?,?,?)");
			$stmt->bindParam(1,$userid);
			$stmt->bindParam(2,$prodid);
			$stmt->bindParam(3,$quan);
			$stmt->bindParam(4,$price);
			$stmt->bindParam(5,$stat);
			$stmt->execute();
			if(!$stmt){
				print_r($db->errorInfo());
			}
		}
		else{
			$stmt = $db->prepare("UPDATE cart SET item_quan=item_quan+1 WHERE prod_id=? AND user_id=?");
			$stmt->bindParam(1,$prodid);
			$stmt->bindParam(2,$userid);
			$stmt->execute();
			$stmt = $db->prepare("SELECT item_quan FROM cart WHERE prod_id=? AND user_id=?");
			$stmt->bindParam(1,$prodid);
			$stmt->bindParam(2,$userid);
			$stmt->execute();
			$x = $stmt->fetch(PDO::FETCH_ASSOC);
			$total = $x["item_quan"] * $price;
			$stmt = $db->prepare("UPDATE cart SET total=? WHERE prod_id=? AND user_id=?");
			$stmt->bindParam(1,$total);
			$stmt->bindParam(2,$prodid);
			$stmt->bindParam(3,$userid);
			$stmt->execute();
			if(!$stmt){
				print_r($db->errorInfo());
			}
		}
	}
	function getCart(){
		$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
		$stmt = $db->prepare("SELECT * FROM cart WHERE status = 'unpaid' AND user_id=?");
		$stmt->bindParam(1,$_SESSION['id']);
		$stmt->execute();
		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $row;
	}
	function getImg($prodid){
		$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
		$stmt = $db->prepare("SELECT prod_img FROM stock WHERE prod_id=?");
		$stmt->bindParam(1,$prodid);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$img = "products/".$row["prod_img"].".jpg";
		return $img;
	}
	function getName($prodid){
		$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
		$stmt = $db->prepare("SELECT prod_name FROM stock WHERE prod_id=?");
		$stmt->bindParam(1,$prodid);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row["prod_name"];
	}
	function getPrice($prodid){
		$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
		$stmt = $db->prepare("SELECT prod_price FROM stock WHERE prod_id=?");
		$stmt->bindParam(1,$prodid);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row["prod_price"];
	}
	function removeItem($prodid,$userid){
		$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
		$stmt = $db->prepare('DELETE FROM cart WHERE prod_id=? AND user_id=?');
		$stmt->bindParam(1,$prodid);
		$stmt->bindParam(2,$userid);
		$stmt->execute();
	}
	function getStockCount($prodid){
		$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
		$stmt = $db->prepare("SELECT prod_quan FROM stock WHERE prod_id=?");
		$stmt->bindParam(1,$prodid);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		echo $row["prod_quan"];
	}
	function deleteStocks($prodid){
		$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
		$stmt = $db->prepare("SELECT prod_img FROM stock WHERE prod_id=?");
		$stmt->bindParam(1,$prodid);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		unlink("products/".$row["prod_img"].".jpg");
		$stmt = $db->prepare("DELETE FROM stock WHERE prod_id=?");
		$stmt->bindParam(1,$prodid);
		$stmt->execute();
	}
	function updateItem($prodid,$quan,$userid){
		$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
		$stmt = $db->prepare("UPDATE cart SET item_quan=? WHERE prod_id=? AND user_id=?");
		$stmt->bindParam(1,$quan);
		$stmt->bindParam(2,$prodid);
		$stmt->bindParam(3,$userid);
		$stmt->execute();
		$stmt = $db->prepare("SELECT prod_price FROM stock WHERE prod_id=?");
		$stmt->bindParam(1,$prodid);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$price = $quan * $row["prod_price"];
		$stmt = $db->prepare("UPDATE cart SET total=? WHERE prod_id=? AND user_id=?");
		$stmt->bindParam(1,$price);
		$stmt->bindParam(2,$prodid);
		$stmt->bindParam(3,$userid);
		$stmt->execute();
	}
	function pay($userid,$total,$date,$name,$address){
		$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
		$stmt = $db->prepare("INSERT INTO sales (total,buyer_id,consignee_name,date_purchased,delivery_address) VALUES(?,?,?,?,?)");
		$stmt->bindParam(1,$total);
		$stmt->bindParam(2,$userid);
		$stmt->bindParam(3,$name);
		$stmt->bindParam(4,$date);
		$stmt->bindParam(5,$address);
		$stmt->execute();
		$stmt = $db->prepare("SELECT * FROM cart WHERE status='unpaid' AND user_id=?");
		$stmt->bindParam(1,$userid);
		$stmt->execute();
		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
		for($x = 0; $x<count($row,COUNT_NORMAL);$x++){
			$stmt = $db->prepare("SELECT * FROM stock WHERE prod_id=?");
			$stmt->bindParam(1,$row[$x]["prod_id"]);
			$stmt->execute();
			$prodcount = $stmt->fetch(PDO::FETCH_ASSOC);
			$stockleft = $prodcount["prod_quan"] - $row[$x]["item_quan"];
			$stmt = $db->prepare("UPDATE stock SET prod_quan=? WHERE prod_id=?");
			$stmt->bindParam(1,$stockleft);
			$stmt->bindParam(2,$row[$x]["prod_id"]);
			$stmt->execute();
			$stmt = $db->prepare("SELECT sold FROM stock WHERE prod_id=?");
			$stmt->bindParam(1,$row[$x]["prod_id"]);
			$stmt->execute();
			$sold = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$totalsold = $sold[$x]["sold"] + $row[$x]["item_quan"];
			$stmt = $db->prepare("UPDATE stock SET sold=? WHERE prod_id=?");
			$stmt->bindParam(1,$totalsold);
			$stmt->bindParam(2,$row[$x]["prod_id"]);
			$stmt->execute();
		}
		$stmt = $db->prepare("UPDATE cart SET status='paid' WHERE user_id=?");
		$stmt->bindParam(1,$userid);
		$stmt->execute();
	}
	function viewUsers(){
		$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
		$stmt = $db->prepare("SELECT * FROM users");
		$stmt->execute();
		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $row;
	}
	function getReport(){
		$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
		$stmt = $db->prepare("SELECT * FROM stock");
		$stmt->execute();
		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $row;	
	}
function getSales(){
	$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
	$stmt = $db->prepare("SELECT SUM(total) AS totalsales FROM sales");
	$stmt->execute();
	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo $row[0]["totalsales"];
}
function getSold(){
	$db = new pdo('mysql:host=localhost;dbname=inventory','root','');
	$stmt = $db->prepare("SELECT SUM(sold) AS totalsold FROM stock");
	$stmt->execute();
	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo $row[0]["totalsold"];
}
}
?>