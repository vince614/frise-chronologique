<?php
namespace App\Models;

use Core\Models\Model;

/**
 * Class UserModel
 * @package App\Models
 */
class UserModel extends Model
{
    /**
     * UserModel constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->_tableName   = $name;
        $this->_entityName  = $name;
        parent::__construct();
    }
}