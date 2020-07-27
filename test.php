<?php

class Test  {
    private static $user = null;
    public static function getUser(){
        if (self::$user ==  null){
            self::$user = new stdClass();
        }
        return self::$user;
    }
}

$a = Test::getUser();
$a['username'] = 'name';
$b = Test::getUser();

var_dump(get_object_vars($b) );
