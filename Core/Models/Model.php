<?php
namespace Core\Models;

use App\App;
use App_Core_Exception;
use PDO;

/**
 * Class Model
 * @package Core\Models
 */
class Model
{

    /**
     * Name of table
     * @var string
     */
    protected $_tableName;

    /**
     * Name of entity
     * @var string
     */
    protected $_entityName;

    /**
     * Filter collection
     * @var array
     */
    protected $_filter = [];

    /**
     * Database model
     * @var Database
     */
    protected $_database;

    public function __construct()
    {
        $this->_database = new Database();
    }

    /**
     * Load an object
     *
     * @param $id
     * @param $field
     * @return bool|mixed
     */
    public function load($id, $field = null)
    {
        $sql = "SELECT * FROM $this->_tableName WHERE id = ?";
        if ($field) $sql = "SELECT * FROM $this->_tableName WHERE $field = ?";
        $req = $this->_database->pdo->prepare($sql);
        $req->execute([$id]);
        return $this->_database->isSuccess($req) ?
            $this->getEntity($this->_entityName, $req->fetch()) :
            false;
    }

    /**
     * Get collection
     *
     * @return array
     */
    public function getCollection()
    {
        $result = [];
        $where = $this->_getWhereClause();
        $req = $this->_database->pdo->prepare("SELECT * FROM $this->_tableName $where");
        $req->execute();
        if ($this->_database->isSuccess($req)) {
            foreach ($req->fetchAll() as $datas) {
                $result[] = $this->getEntity($this->_entityName, $datas);
            }
            return $result;
        }
        return [];
    }


    /**
     * Add attribut to filter
     *
     * @param $attribut
     * @param $value
     * @return $this
     */
    public function addAttributToFilter($attribut, $value)
    {
        $this->_filter[$attribut] = $value;
        return $this;
    }

    /**
     * Get where clause from filter
     *
     * @return string
     */
    private function _getWhereClause()
    {
        $sql = "";
        $i = 0;
        foreach ($this->_filter as $attribut => $value) {
            if ($i === 0) $sql .= "WHERE ";
            if ($i >= 1) $sql .= " AND ";
            $sql .= is_array($value) ?
                "$attribut $value[0] '$value[1]'" :
                "$attribut = '$value'";
            $i++;
        }
        return $sql;
    }

    /**
     * Save an object datas
     *
     * @param $object
     * @param $tableName
     * @return bool
     * @throws App_Core_Exception
     */
    public function save($object, $tableName)
    {
        $attributes = $object->getAttributes();
        if ($attributes) {
            $array = [];
            foreach ($attributes as $attribut) {
                $method = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attribut)));
                if (is_callable([$object, $method])) {
                    $array[$attribut] = $object->$method();
                }
            }
            return $this->_database->updateMultiple($tableName, $array);
        }
        App::throwException("Erreur lors de la sauvegarde, pas d'attributs trouvés..");
    }

    /**
     * Delete an object from database
     *
     * @param $object
     * @param $tableName
     * @return bool
     * @throws App_Core_Exception
     */
    public function delete($object, $tableName)
    {
        if (is_callable([$object, 'getId'])) {
            $objectId = $object->getId();
            $req = $this->_database->pdo->prepare("DELETE FROM $tableName WHERE id = ?");
            $req->execute([$objectId]);
            return $this->_database->isSuccess($req);
        }
        App::throwException("Erreur lors de la suppression, pas d'id trouvé");
    }

    /**
     * Get entity
     *
     * @param $entityName
     * @param $datas
     * @return bool
     */
    public function getEntity($entityName, $datas)
    {
        $entityName = ucfirst($entityName) . "Entity";
        $className = "App\\Entity\\" . $entityName;
        if (class_exists($className)) {
            return new $className($datas);
        }
        return false;
    }
}