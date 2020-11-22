<?php
$dbhost = "localhost";
$dbusername = "root";
$dbuserpassword = "";
$default_dbname = "books_test";

include($_SERVER["DOCUMENT_ROOT"]."/db.php");
$db=new db($dbhost,$dbusername,$dbuserpassword,$default_dbname);

$url_full = getenv('REQUEST_URI');
if (stristr($url_full, "?") != false) {
    $uriToParce = substr($url_full, 0, strpos($url_full, "?"));
    $gets = substr($url_full, strpos($url_full, "?") + 1);
    $getr = explode("&", $gets);
    foreach ($getr as $k => $v) {
        $gr = explode("=", $v);
        $_GETP[$gr[0]] = $gr[1];
    }
} else {
    $uriToParce = $url_full;
    $_GETP = array();
}

$c=@file_get_contents("template.html");
echo replases($c);
function replases($c){
    $c = str_replace("[%option_authors%]", get_autors(), $c);
    $c = str_replace("[%books_items%]", get_books(), $c);
    $c = str_replace("[%option_genre%]", get_genre(), $c);
    $c = str_replace("[%pagination%]", get_pagination(), $c);

    return $c;
}
function get_books(){
    global $db, $_GETP;
    $page='';
    if(isset($_GETP['page'])){
        $page = $_GETP['page'];
    }
    $sql = "select `b`.`id`,`b`.`title`, `a`.`name` as author, `g`.`name` as genre, `b`.`date`, `b`.`description` from `books` as `b` inner join `author` as `a` on `b`.`author`=`a`.`id` inner join `genre` as `g`  on `b`.`genre`=`g`.`id` ";
    $sql.=empty($page)?"limit 0, 6":"limit ".($page*6-6).", ".$page*6;
    $res = $db->q($sql);
    if ($res && $db->num_rows($res) > 0) {
        $result = "<div class='books'>";
        for ($i = 0; $i < $db->num_rows($res); $i++) {
            $data = @mysqli_fetch_array($res);
            $result .= "<div class='book' style='width:500px'>
                <img class='book-img-top' src='/upload/".$data['id'].".jpg' style='width:100%'>
                <div class='item-body'>
                    <div class='item-title'>".$data['title']."</div>
                    <div class='item-text'>".$data['description']."</div>
                    <div class='item-genre'>".$data['genre']."</div>
                    <div class='item-author'>".$data['author']."</div>
                    <div class='item-date'>".$data['date']."</div>
                    <button class='btn btn-primary change'>Изменить</button>
                    <button class='btn btn-danger delete'>Удалить</button>
                    <input type = 'hidden' class='id_book' value='".$data['id']."'>
                </div></div>";
        }
        return $result."</div>";
    } else return "нет книг";
}

function get_autors(){
    global $db;
    $sql = "select * from `author`;";
    $res = $db->q($sql);
    if ($res && $db->num_rows($res) > 0){
        $result = "";
      for ($i = 0; $i < $db->num_rows($res); $i++){
         $data = @mysqli_fetch_array($res);
         $result .=  "<option value='".$data['id']."'>".$data['name']."</option>";
      }  
    }
    return $result;
}
function get_genre(){
    global $db;
    $sql = "select * from `genre`;";
    $res = $db->q($sql);
    if ($res && $db->num_rows($res) > 0){
        $result = "";
      for ($i = 0; $i < $db->num_rows($res); $i++){
         $data = @mysqli_fetch_array($res);
         $result .=  "<option value='".$data['id']."'>".$data['name']."</option>";
      }  
    }
    return $result;
}
function get_pagination(){
    global $_GETP, $db;
        if(isset($_GETP['page'])){
            $page = $_GETP['page'];
        }
        $sql='SELECT COUNT(id) FROM `books`';
        $res = $db->q($sql);
        $data = $db->one($res);
        $count_items =  $data['COUNT(id)'];
        $out="";
        if($count_items>6) {
            $k = substr(getenv('REQUEST_URI'), 1);
            $page_count = $count_items/6;
            $out .= '<ul class="pagination">';
            for($i=1; $i<$page_count+1; $i++) {
                if(empty($page)) $page=1;
                $active= $page==$i?"active":"";
                $out .= '<li class="page-item '.$active.'"><a class="page-link" href="?page='.$i.'">'.$i.'   </a></li>';
            }
            $out .= '</ul>';
        }
        return $out;
}

?>