<?php

namespace Core\Configuration;

use Core\Models\Database;
use PDO;

/**
 * Class Config
 */
class Config extends Database
{

    /**
     * Config code
     */
    const UNSECURE_URL_CONFIG_CODE      = 'unsecure_url';
    const SECURE_URL_CONFIG_CODE        = 'secure_url';
    const MAINTENANCE_MODE_CONFIG_CODE  = 'maintenance_mode';
    const LOCAL_MODE_CONFIG_CODE        = 'local_mode';

    /**
     * Get config
     *
     * @param $code
     * @return bool|mixed
     */
    public function getConfig($code)
    {
        $req = $this->pdo->prepare('SELECT * FROM config WHERE code = ?');
        $req->execute([$code]);
        return $this->isSuccess($req) ?
            $req->fetch(PDO::FETCH_OBJ)->value :
            false;
    }

    /**
     * Add new config
     *
     * @param $code
     * @param $value
     * @return bool
     */
    public function addConfig($code, $value)
    {
        $req = $this->pdo->prepare('INSERT INTO config (code, value) VALUES (?, ?)');
        $req->execute([$code, $value]);
        return $this->isSuccess($req);
    }

    /**
     * Update config
     *
     * @param $code
     * @param $value
     * @return bool
     */
    public function updateConfig($code, $value)
    {
        $req = $this->pdo->prepare('UPDATE config SET value = ? WHERE code = ?');
        $req->execute([$value, $code]);
        return $this->isSuccess($req);
    }

    /**
     * Delete config
     *
     * @param $code
     * @return bool
     */
    public function deleteConfig($code)
    {
        $req = $this->pdo->prepare('DELETE FROM config WHERE code = ?');
        $req->execute([$code]);
        return $this->isSuccess($req);
    }

}