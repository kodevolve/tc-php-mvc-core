<?php
namespace app\core;

use app\core\db\DB_Model;

abstract class User_Model extends DB_Model{

    abstract public function get_display_name(): string;

}