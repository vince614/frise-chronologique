<?php
namespace Core\Models;

use Dotenv\Dotenv;
use PDO;
use PDOStatement;

/**
 * Class Database
 * @package Core
 */
class Database
{
    /**
     * @var PDO
     */
    public $pdo;

    /**
     * Core_Mysql constructor.
     */
    public function __construct()
    {
        self::initEnv();
        self::initPDO();
    }

    /**
     * Init PDO
     */
    private function initPDO()
    {
        if (!$this->pdo) {
            $this->pdo = new PDO(
                "mysql:host={$_ENV['DATABASE_HOSTNAME']};dbname={$_ENV['DATABASE_NAME']};charset={$_ENV['DATABASE_CHARSET']}",
                $_ENV['DATABASE_USER'],
                $_ENV['DATABASE_PASSWORD']
            );
        }
    }

    /**
     * Init environement variable
     */
    private function initEnv()
    {
        $dotenv = Dotenv::createImmutable(ROOT);
        $dotenv->load();
    }

    /**
     * Get PDO
     *
     * @return PDO
     */
    public function getPDO()
    {
        return $this->pdo;
    }

    /**
     * Check if request is success
     *
     * @param PDOStatement $req
     * @return bool
     */
    public function isSuccess(PDOStatement $req)
    {
        return $req->rowCount() > 0;
    }


    /**
     * Update multiple elements
     *
     * @param $table
     * @param $params
     * @return bool
     */
    public function updateMultiple($table, $params)
    {
        $i = 0;
        $sql = "UPDATE $table SET ";
        $paramsCount = count($params);
        $array = [];
        foreach ($params as $field => $value) {
            if ($field === "id") continue;
            $sql .= $paramsCount - 2 == $i ?
                "$field = ? " :
                "$field = ?, ";
            $array[] = $value;
            $i++;
        }
        $array[] = (int) $params['id'];
        $sql .= "WHERE id = ?";

        $req = Database::getPDO()->prepare($sql);
        $req->execute($array);
        return Database::isSuccess($req);

    }
}