<?php

$allowed = array('page', 'object');
$vars = array_intersect_key($_REQUEST, array_flip($allowed));
extract($vars);

class Redis_Controller {

  public function __construct() {

     $this->redis = new Redis();
     $this->redis->connect('127.0.0.1', 6379);
     $this->redis->select(0);

  }

  function save_it($object){
    $this->redis->sadd('user:01:faves', $object);
    echo $this->redis->smembers('user:01:faves');

  }

}

$redis_start = new Redis_Controller();

switch($page) {
    case "favorite";
      $redis_start->save_it($object);

    break;
    default:
       echo "Something is wrong";

     }
?>
