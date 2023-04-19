<?php
namespace Piwik\Plugins\LoginSimpleSamlPhp;

use Piwik\Auth;
use Piwik\Container\StaticContainer;
use Piwik\Url;
use Piwik\View;

class LoginSimpleSamlPhp extends \Piwik\Plugin
{
    public Auth $auth;

    public function __construct()
    {
        parent::__construct();

        $this->auth = new LoginSimpleSamlPhpAuth();
    }

    public function registerEvents(): array
    {
        return [
            'Request.initAuthenticationObject' => 'initAuthenticationObject',
            'Template.loginNav' => 'loginNav',
        ];
    }

    public function initAuthenticationObject(): void
    {
        StaticContainer::getContainer()->set(Auth::class, $this->auth);
    }

    public function loginNav(string &$out, string $payload = null): void
    {
        if (!$this->auth->ssp || $payload !== 'bottom') {
            return;
        }

        $view = new View('@LoginSimpleSamlPhp/loginNav');
        $view->href = $this->auth->ssp->getLoginURL(Url::getCurrentUrl());
        $view->pluginTitle = $this->auth->settings->pluginTitle->getValue();
        $view->pluginButton = $this->auth->settings->pluginButton->getValue();
        $view->defaultTitle = $this->auth->settings->defaultTitle->getValue();

        $out .= $view->render();
    }
}
