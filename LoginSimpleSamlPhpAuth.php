<?php
namespace Piwik\Plugins\LoginSimpleSamlPhp;

use \Piwik\AuthResult;
use \Piwik\Plugins\Login\Auth;
use \Piwik\Plugins\UsersManager\Model;

class LoginSimpleSamlPhpAuth extends Auth
{
    public SystemSettings $settings;
    public $ssp;

    public function __construct()
    {
        parent::__construct();

        $this->settings = new SystemSettings();

        $path = $this->settings->path->getValue();
        $authSource = $this->settings->authSource->getValue();

        if (!$path || !$authSource) {
            return;
        }

        $file = rtrim($path, DIRECTORY_SEPARATOR) .
            DIRECTORY_SEPARATOR . 'lib' .
            DIRECTORY_SEPARATOR . '_autoload.php';

        if (file_exists($file)) {
            require_once $file;
        }

        if (!class_exists('\SimpleSAML\Auth\Simple')) {
            return;
        }

        try {
            $ssp = new \SimpleSAML\Auth\Simple($authSource);
            $ssp->getAuthSource();
        } catch (\SimpleSAML\Error\AuthSource $exception) {
            return;
        }

        $this->ssp = $ssp;
    }

    public function getName(): string
    {
        return 'LoginSimpleSamlPhp';
    }

    public function authenticate(): AuthResult
    {
        if ($this->ssp) {
            $attribute = $this->settings->attribute->getValue();
            $format = $this->settings->format->getValue();
            $email = $this->settings->email->getValue();

            $attributes = $this->ssp->getAttributes();

            if (!empty($attributes[$attribute])) {
                $model = new Model();

                foreach ($attributes[$attribute] as $value) {
                    if ($format) {
                        $value = sprintf($format, $value);
                    }

                    $user = $email
                        ? $model->getUserByEmail($value)
                        : $model->getUser($value);

                    if (empty($user)) {
                        throw new \Exception(\sprintf(
                            '%s matching "%s" not found.',
                            ($email ? 'Email' : 'Username'),
                            $value
                        ));
                    }

                    $this->login = $user['login'];

                    return new AuthResult(
                        empty($user['superuser_access'])
                            ? AuthResult::SUCCESS
                            : AuthResult::SUCCESS_SUPERUSER_AUTH_CODE,
                        $this->login,
                        $this->token_auth
                    );
                }
            }
        }

        return parent::authenticate();
    }
}
