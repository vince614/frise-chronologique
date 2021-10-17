<?php
namespace App\Entity;

use Core\Entity\Entity;

/**
 * Class UserEntity
 */
class UserEntity extends Entity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $avatar;

    /**
     * @var int
     */
    private $created_at;

    /**
     * @var int
     */
    private $is_admin;

    /**
     * @return int
     */
    public function getId()
    {
        return (int) $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @return int
     */
    public function getCreatedAt()
    {
        return (int) $this->created_at;
    }

    /**
     * @return int
     */
    public function getIsAdmin()
    {
        return $this->is_admin;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param $created_at
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param $avatar
     * @return $this
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * @param int $is_admin
     * @return UserEntity
     */
    public function setIsAdmin($is_admin)
    {
        $this->is_admin = $is_admin;
        return $this;
    }
}