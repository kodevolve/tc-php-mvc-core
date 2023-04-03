<?php
namespace app\core\db;

use app\core\Application;

class Database {
    public \PDO $pdo;

    public function __construct(array $config) {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';

        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function apply_migrations() {
        // $this->drop_mvc_database();
        $this->create_mvc_database();
        $this->create_migrations_table();
        $applied_migrations = $this->get_applied_migrations();

        $new_migrations = [];

        $files = scandir(Application::$ROOT_DIR.'/migrations');
        $to_apply_migrations = array_diff($files, $applied_migrations);
        foreach ($to_apply_migrations as $migration) {
            if ('.' === $migration || '..' === $migration) {
                continue;
            }
            require_once Application::$ROOT_DIR.'/migrations/'.$migration;
            $class_name = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $class_name;
            $this->log("Applying migration {$migration}".PHP_EOL);
            $instance->up();
            $this->log("Applied migration {$migration}".PHP_EOL);
            $new_migrations[] = $migration;
        }
        if (!empty($new_migrations)) {
            $this->save_migrations($new_migrations);
        } else {
            $this->log('All migrations are applied').PHP_EOL;
        }
    }

    public function drop_mvc_database() {
        $this->pdo->exec('DROP DATABASE IF EXISTS mvc_framework');
    }
    public function create_mvc_database() {
        $this->pdo->exec('CREATE DATABASE IF NOT EXISTS mvc_framework');
        $this->pdo->exec('USE mvc_framework');
    }
    public function create_migrations_table() {
        $this->pdo->exec('
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;
        ');
    }
    public function get_applied_migrations() {
        $statement = $this->pdo->prepare('SELECT migration from migrations');
        $statement->execute();

        return $statement->fetchAll((\PDO::FETCH_COLUMN));
    }

    public function save_migrations(array $migrations) {
        $arr = array_map(fn($m) => "('{$m}')", $migrations);
        $str = implode(',',$arr);

        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES
            {$str}
        ");
        $statement->execute();
    }
    public function prepare($sql) {
        return $this->pdo->prepare($sql);
    }
    protected function log($message) {
        echo '['.date('Y-m-d H:i:s').'] - '.$message.PHP_EOL;
    }
}