<?php
namespace daviscodedev\phpmvc\db;

use daviscodedev\phpmvc\Application;
use daviscodedev\phpmvc\Model;

#[\AllowDynamicProperties]
abstract class DB_Model extends Model {

    abstract public function table_name(): string;
    abstract public function attributes(): array;
    abstract public function primary_key(): string;

    public function save() {
        $table_name = $this->table_name();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $statement = self::prepare("INSERT INTO $table_name (first_name, last_name, email, password, status) VALUES(".implode(',',$params).")");
        foreach($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }
        $statement->execute();
        return true;
    }

    public function find_one($where) {
        $table_name = static::table_name();
        $attributes = array_keys($where);
        // note: "SELECT * FROM $table_name WHERE email = :email AND first_name = :first_name";
        $sql = implode("AND ",array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $table_name WHERE $sql");
        foreach($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    public static function prepare($sql) {
        return Application::$app->db->pdo->prepare($sql);
    }


}