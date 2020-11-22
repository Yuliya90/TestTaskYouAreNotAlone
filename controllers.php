<?php  
$dbhost = "localhost";
$dbusername = "root";
$dbuserpassword = "";
$default_dbname = "books_test";

include($_SERVER["DOCUMENT_ROOT"]."/db.php");
$db=new db($dbhost,$dbusername,$dbuserpassword,$default_dbname);
	if(isset($_POST['create_title'])){

		echo create_book($_POST);
	}
	var_dump($_POST);
	if(isset($_POST['update_title'])){
		echo update_book($_POST);
	}
	if(isset($_POST['delete_title'])){
		echo delete_book($_POST);
	}

if(isset($_POST["action"])){	
	if ($_POST["action"] == "update_form") {
		$sql = "select *,date_format(`date`,'%Y.%m.%d') as `fdate` from `books` where `id` = '".$_POST['id']."'";
		$res = $db->q($sql);
		if ($res && $db->num_rows($res) > 0) {
	        $data = @mysqli_fetch_array($res);
	        $str_out = $data["title"].','.$data["fdate"].','.$data["author"].','.$data["genre"].','.$data["description"];
		echo $str_out;
	    }
	}
	if($_POST["action"] == "delete"){
		$sql = "delete from `books` where `id` = '".$_POST['id']."'";
		echo $sql;
		$res=$db->q($sql);
		if ($res){
			$path = "upload/" . $id  . ".jpg";
    		if(file_exists($path)){
    			unlink($path);
    		}
		}
	}
	if($_POST["action"] == "aut"){
		$sql = "select `b`.`id`,`b`.`title`, `a`.`name` as author, `g`.`name` as genre, `b`.`date`, `b`.`description` from `books` as `b` inner join `author` as `a` on `b`.`author`=`a`.`id` inner join `genre` as `g`  on `b`.`genre`=`g`.`id` where `b`.`author`= '".$_POST['id']."';";
		$res = $db->q($sql);
    	if ($res && $db->num_rows($res) > 0) {
        	$result = "";
        	for ($i = 0; $i < $db->num_rows($res); $i++) {
        		$data = @mysqli_fetch_array($res);
	        	$result .= $data["id"].','.$data["title"].','.$data["date"].','.$data["author"].','.$data["genre"].','.$data["description"]."/";
        	}
        	echo $result;
    	}
	}
		if($_POST["action"] == "gen"){
		$sql = "select `b`.`id`,`b`.`title`, `a`.`name` as author, `g`.`name` as genre, `b`.`date`, `b`.`description` from `books` as `b` inner join `author` as `a` on `b`.`author`=`a`.`id` inner join `genre` as `g`  on `b`.`genre`=`g`.`id` where `b`.`genre`= '".$_POST['id']."';";
		$res = $db->q($sql);
    	if ($res && $db->num_rows($res) > 0) {
        	$result = "";
        	for ($i = 0; $i < $db->num_rows($res); $i++) {
        		$data = @mysqli_fetch_array($res);
	        	$result .= $data["id"].','.$data["title"].','.$data["date"].','.$data["author"].','.$data["genre"].','.$data["description"]."/";
        	}
        	echo $result;
    	}
	}
	
	if($_POST["action"] == "search"){
		$sql = "select `b`.`id`,`b`.`title`, `a`.`name` as author, `g`.`name` as genre, `b`.`date`, `b`.`description` from `books` as `b` inner join `author` as `a` on `b`.`author`=`a`.`id` inner join `genre` as `g` on `b`.`genre`=`g`.`id` where `b`.`title` like '%".$_POST['search']."%' or `b`.`description` like '%".$_POST['search']."%';";
		$res = $db->q($sql);
    	if ($res && $db->num_rows($res) > 0) {
        	$result = "";
        	for ($i = 0; $i < $db->num_rows($res); $i++) {
        		$data = @mysqli_fetch_array($res);
	        	$result .= $data["id"].','.$data["title"].','.$data["date"].','.$data["author"].','.$data["genre"].','.$data["description"]."/";
        	}
        	echo $result;
    	}
	}

} 	
function aut($arr_aut){
	global $db;
    $id=" ";
    $sql = "select * from `books` where `author`= '".$arr_aut."';";
    console.log ($sql);
}
function gen($arr_gen){
	global $db;
    $id=" ";
    $sql = "select * from `books` where `genre`= '".$arr_gen."';";
    console.log ($sql);
}
function create_book($arr_input){
    global $db;
    $id=" ";
    $sql = "insert into `books` set `title`= '".$arr_input['create_title']."', `date` = '".$arr_input['date']."', `author` = '".$arr_input['author']."', `genre` = '".$arr_input['ganre']."', `description` = '".$arr_input['description']."';";
	$res = $db->q($sql);
    if ($res) {
    	$id=mysqli_insert_id($db->link);
    }
    	add_img($id);
     
    return header('Location: ' . "/");
}
function update_book($arr_update){
	var_dump($arr_update);
	global $db;
	$id=$arr_update['id_pook'];
	$title = " "; 
	$date = " ";
	$author = " "; 
	$genre = " "; 
	$descr = " ";
	if(isset($arr_update['update_title'])){ $title=$arr_update['update_title'];}
	if(isset($arr_update['date'])) $date=$arr_update['date'];
	if(isset($arr_update['author'])) $author=$arr_update['author'];
	if(isset($arr_update['ganre'])) $genre=$arr_update['ganre'];
	if(isset($arr_update['description'])) $descr=$arr_update['description'];

	$sql = "update `books` set `title`= '".$title."', `date` = '".$date."', `author` = '".$author."', `genre` = '".$genre."', `description` = '".$descr."' where `id`='".$id."';";
	$res=$db->q($sql);
	if($res){
		echo "готово";
	}
	add_img($id);
	return header('Location: ' . "/");
}
function add_img($id){
	 if(isset($_FILES["image"]["name"])){

		$path = "upload/" . $id  . ".jpg";
    	if(file_exists($path)){
    		unlink($path);
    	}
        if (!@move_uploaded_file($_FILES["image"]['tmp_name'], $path)) {
        die("error save image");
        }
	}
}

?>