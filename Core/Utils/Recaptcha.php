<?php
namespace Core\Utils;

use Core\Configuration\Config;
use Core\Models\Database;

require_once 'Request.php';

/**
 * Class GoogleRecaptcha
 * @package Core\Utils
 */
class Recaptcha
{
    const GOOGLE_RECAPTCHA_API_URL = "https://www.google.com/recaptcha/api/siteverify";

    private $_publicKey;
    private $_secretKey;

    /**
     * @var Request
     */
    protected $_request;

    public function __construct()
    {
        $config = new Config();
        $this->_publicKey = $config->getConfig('public_key');
        $this->_secretKey = $config->getConfig('secret_key');
        $this->_request = new Request();
    }

    /**
     * Google Recaptcha
     *
     * @return bool
     */
    public function recaptchaVerification()
    {
        if ($this->_request->isPost()) {
            $gRecaptchaResponse = $this->_request->getPost('g-recaptcha-response');
            if ($gRecaptchaResponse) {
                return $this->validateCaptcha(
                    $gRecaptchaResponse,
                    $this->_secretKey
                );
            }
        }
        return false;
    }

    /**
     * Validate captcha
     *
     * @param $response
     * @param $secretKey
     * @return bool
     */
    public function validateCaptcha($response, $secretKey)
    {
        $verifyResponse = file_get_contents(self::GOOGLE_RECAPTCHA_API_URL . '?secret=' . $secretKey . '&response=' . $response);
        $responseData = json_decode($verifyResponse);
        if ($responseData->success) return true;
        return false;
    }
}