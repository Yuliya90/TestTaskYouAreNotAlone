<?php

class db{
    function __construct($host,$user,$pass,$dbname){
        $this->link = mysqli_connect($host, $user, $pass, $dbname);
        if(!$this->link) die("<html><head><META http-equive=\"content-type\" content=\"text/html; charset=utf-8\"></head><body bgcolor=white><p>Соединение с базой данных невозможно. Повторите попытку или проверьте настройки.<br>Приносим извинения за временные неудобства.</p></body></html>");
         mysqli_query($this->link,"set names `utf8`");
         mysqli_query($this->link,"character set `utf8`");        
         //mysqli_query($this->link,"SET GLOBAL time_zone = '+8:00';");        
         mysqli_query($this->link,"SET time_zone = '+8:00';");        
    } 
    public function sql_text($text){
        
         if (@get_magic_quotes_gpc()) {
             $text = stripslashes($text);
         }
         if(@function_exists("mysqli_real_escape_string")){
             $text=mysqli_real_escape_string($this->link,$text);
         }
         elseif(@function_exists("magic_quotes_gpc")){
             $text=@magic_quotes_gpc($text);
         }
         else{
             $text=@stripslashes($text);
             $text=@addslashes($text);
         }
         
         return $text;
    }
    public static function sql_date($text,$only_date=0){
        if($only_date){
            $date_ar=explode(".",$text);
        }
        else{
            $date_ar=explode(".",substr($text,0,strpos($text," ")));
        }
        $sql_format=$date_ar[2]."-".$date_ar[1]."-".$date_ar[0].(!$only_date?" ".substr($text,strpos($text," ")+1).":00":"");
        return $sql_format;
    } 
    public function clearout($val,$br=0,$tags=0,$quot=0,$maxlen=16000){
         if(!$quot){
            $val=@str_replace("'","",$val);
            $val=@str_replace("\"","",$val);
         }
         else{
            $val=@str_replace("\"","&quot;",$val);
         }
         if($maxlen&&strlen($val)>$maxlen) $val=@substr($val,0,$maxlen);
         if(!$tags) $val=@strip_tags($val);
         if($br) $val=@nl2br($val);
         $val=trim($val);
         return $val;
    }
    public function q($sql){
         $res=mysqli_query($this->link,$sql);
         return $res;
    }
    public function num_rows($res){
         $nr=mysqli_num_rows($res);
         return $nr;
    }
    public function one($res){
         $row=mysqli_fetch_assoc($res);
         return $row;
    }
    public function err($res){
         $error=mysqli_error($this->link);
         return $error;
    }
    public function row($res){
         $row=mysqli_fetch_assoc($res);
         return $row;
    }
    public function insert_id(){
         return mysqli_insert_id($this->link);
    }
}
?>