<?php
namespace Piwik\Plugins\LoginSimpleSamlPhp;

use Piwik\Settings\Setting;
use Piwik\Settings\FieldConfig;

class SystemSettings extends \Piwik\Settings\Plugin\SystemSettings
{
    public Setting $path;
    public Setting $authSource;
    public Setting $attribute;
    public Setting $format;
    public Setting $email;
    public Setting $pluginTitle;
    public Setting $pluginButton;
    public Setting $defaultTitle;

    protected function init()
    {
        $this->path = $this->makeSetting(
            'path',
            '',
            FieldConfig::TYPE_STRING,
            function (FieldConfig $field) {
                $field->title = 'Path';
                $field->uiControl = FieldConfig::UI_CONTROL_TEXT;
                $field->introduction = 'SimpleSAMLphp';
                $field->description =
                    'Filesystem path to the SimpleSAMLphp installation.';

                $field->validate = new \Piwik\Validators\Email();
            }
        );

        $this->authSource = $this->makeSetting(
            'authSource',
            '',
            FieldConfig::TYPE_STRING,
            function (FieldConfig $field) {
                $field->title = 'Auth Source';
                $field->uiControl = FieldConfig::UI_CONTROL_TEXT;
                $field->description =
                    'The ID of the SimpleSAMLphp authentication source.';
            }
        );

        $this->attribute = $this->makeSetting(
            'attribute',
            'uid',
            FieldConfig::TYPE_STRING,
            function (FieldConfig $field) {
                $field->title = 'Attribute';
                $field->uiControl = FieldConfig::UI_CONTROL_TEXT;
                $field->introduction = 'User Matching';
                $field->description =
                    'Attribute provided by SimpleSAMLphp to match with Matomo'.
                    ' user data (e.g. uid).';
            }
        );

        $this->format = $this->makeSetting(
            'format',
            '%s',
            FieldConfig::TYPE_STRING,
            function (FieldConfig $field) {
                $field->title = 'Attribute Format';
                $field->uiControl = FieldConfig::UI_CONTROL_TEXT;
                $field->description =
                    'Format the attribute via sprintf() before matching. Use'.
                    ' %s as a placeholder for the attribute value.';
            }
        );

        $this->email = $this->makeSetting(
            'email',
            false,
            FieldConfig::TYPE_BOOL,
            function (FieldConfig $field) {
                $field->title = 'Match Email Address';
                $field->uiControl = FieldConfig::UI_CONTROL_CHECKBOX;
                $field->description =
                    'If checked, match the Matomo user\'s email address to'.
                    ' the formatted attribute. Otherwise, match the username.';
            }
        );

        $this->pluginTitle = $this->makeSetting(
            'pluginTitle',
            '',
            FieldConfig::TYPE_STRING,
            function (FieldConfig $field) {
                $field->title = 'Plugin Title';
                $field->uiControl = FieldConfig::UI_CONTROL_TEXT;
                $field->introduction = 'Login Form';
                $field->description =
                    'Title to display above the button to log in via the'.
                    ' SimpleSAMLphp plugin. Will not be displayed if left'.
                    ' blank.';
            }
        );

        $this->pluginButton = $this->makeSetting(
            'pluginButton',
            '',
            FieldConfig::TYPE_STRING,
            function (FieldConfig $field) {
                $field->title = 'Plugin Button';
                $field->uiControl = FieldConfig::UI_CONTROL_TEXT;
                $field->description =
                    'Label for the button to log in via the SimpleSAMLphp'.
                    ' plugin. Will use "SimpleSAMLphp Log In" if left blank.';
            }
        );

        $this->defaultTitle = $this->makeSetting(
            'defaultTitle',
            '',
            FieldConfig::TYPE_STRING,
            function (FieldConfig $field) {
                $field->title = 'Default Title';
                $field->uiControl = FieldConfig::UI_CONTROL_TEXT;
                $field->description =
                    'Title to display above the default Omeka login form.'.
                    ' Will not be displayed if left blank.';
            }
        );
    }
}
