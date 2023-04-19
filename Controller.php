<?php
namespace Piwik\Plugins\LoginSimpleSamlPhp;

use \Piwik\Config;
use \Piwik\Piwik;
use \Piwik\Url;

class Controller extends \Piwik\Plugins\Login\Controller
{
    public function logout()
    {
        Piwik::postEvent('Login.logout', [Piwik::getCurrentUserLogin()]);

        self::clearSession();

        $logoutUrl = @Config::getInstance()->General['login_logout_url'];

        if ($this->auth->ssp->isAuthenticated()) {
            $this->auth->ssp->logout($logoutUrl);
        }

        if (empty($logoutUrl)) {
            Piwik::redirectToModule('CoreHome');
        } else {
            Url::redirectToUrl($logoutUrl);
        }
    }
}
