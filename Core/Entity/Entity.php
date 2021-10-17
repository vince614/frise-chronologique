<?php

namespace Core\Entity;

use App_Core_Exception;
use Core\Models\Model;

/**
 * Class Entity
 * @package Core\Entity
 */
class Entity
{

    /**
     * @var array
     */
    private $_attributes = [];

    /**
     * Entity constructor.
     * @param array $datas
     */
    public function __construct(Array $datas)
    {
        if (!empty($datas)) {
            $this->hydrate($datas);
        }
    }

    /**
     * Get magic method
     *
     * @param  string $key
     * @return string getter
     */
    public function get($key)
    {
        $method = 'get' . ucfirst($key);
        $this->$key = $this->$method();
        return $this->$key;
    }

    /**
     * Hydrate entity
     *
     * @param $datas
     */
    public function hydrate($datas)
    {
        foreach ($datas as $attribut => $value) {
            $this->_attributes[] = $attribut;
            $method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attribut)));
            if (is_callable([$this, $method])) {
                $this->$method($value);
            }
        }
    }

    /**
     * Get entity attribut
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->_attributes;
    }

    /**
     * Save entity
     *
     * @throws App_Core_Exception
     */
    public function save()
    {
        $model = new Model;
        $model->save($this, $this->getTableName());
    }

    /**
     * Delete entity
     *
     * @throws App_Core_Exception
     */
    public function delete()
    {
        $model = new Model;
        $model->delete($this, $this->getTableName());
    }

    /**
     * Get table name
     *
     * @return false|string|string[]|null
     */
    private function getTableName()
    {
        $className = get_class($this);
        $className = @end(explode("\\", $className));
        $className = str_replace('Entity', '', $className);
        return mb_strtolower($className);
    }

}