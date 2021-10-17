<?php

namespace Core\Acl;

use App\App;
use App\Models\UserModel;
use Core\Controllers\ErrorController;
use Core\Utils\Request;

/**
 * Class ACL
 * @package Core\Acl
 */
class ACL
{

    /**
     * ACL user level
     */
    const EVERYONE      = 0;
    const NOT_LOGGED_IN = 1;
    const LOGGED_IN     = 2;
    const ADMIN         = 3;

    /**
     * @var array
     */
    private $acl;

    /**
     * @var Request
     */
    protected $request;

    /**
     * Acl constructor.
     * @param $acl
     */
    public function __construct($acl)
    {
        $this->acl = $acl;
        $this->request = new Request();
    }

    /**
     * Run ACL checker
     *
     * @return ErrorController
     */
    public function run()
    {
        $acces = $this->checkACL();
        if (!$acces) {
            return new ErrorController('Errors.403');
        }
    }

    /**
     * Check is user can access to endpoint
     *
     * @return bool
     */
    protected function checkACL()
    {
        $userLvl = $this->getUserLvl();
        if ($this->acl == self::EVERYONE) return true;
        if (is_array($this->acl)) {
            foreach ($this->acl as $acl) {
                if ($userLvl >= $acl) return true;
            }
            return false;
        }
        return $userLvl >= $this->acl;
    }

    /**
     * Get user level
     *
     * @return int
     */
    protected function getUserLvl()
    {
        if ($user = $this->request->getSession('user')) {
            /** @var UserModel $userModel */
            $userModel = App::getModel('user');
            $user = $userModel->load($user['id']);
            if ($user) {
                return $user->getIsAdmin() == 1 ?
                    self::ADMIN :
                    self::LOGGED_IN;
            }
        }
        return self::NOT_LOGGED_IN;
    }

}