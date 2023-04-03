<?php
namespace daviscodedev\phpmvc;

use daviscodedev\phpmvc\db\DB_Model;

abstract class User_Model extends DB_Model{

    abstract public function get_display_name(): string;

}