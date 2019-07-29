<?php
/**
 * Initializer class to load admin section
 * Final class cannot be inherited anymore. We use them when creating new instances
 * @package Deals
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Deals\Controllers\Admin;
use Deals\Controllers\Admin\Demos\DemosController;
use Deals\Controllers\Admin\Demos\ImportDemoController;
use Deals\Controllers\Admin\Manual\ManualController;
use Deals\Controllers\Admin\Settings\ChangeGlobalSettingsController;
use Deals\Controllers\Admin\Settings\SettingsController;
use Deals\Controllers\Admin\Status\SingleController;
use Deals\Controllers\Admin\Deal\AddEditDealController;
use Deals\Controllers\Admin\Deal\DealController;
use Deals\Models\Configuration\ConfigurationInterface;
use Deals\Models\Language\LanguageInterface;
use Deals\Models\Validation\StaticValidator;

final class SingleMenuController
{
    private $conf 	                = NULL;
    private $lang 		            = NULL;
    private $errorMessages          = array();

    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitization will kill the system speed
        $this->lang = $paramLang;
    }


    /****************************************************************************************/
    /****************************************** MENU METHODS ********************************/
    /****************************************************************************************/

    /**
     * @param int $paramMenuPosition
     */
    public function addStatusMenu($paramMenuPosition = 97)
    {
        $validMenuPosition = intval($paramMenuPosition);
        $iconURL = $this->conf->getRouting()->getAdminImagesURL('Plugin.png');
        $urlPrefix = $this->conf->getPluginURL_Prefix();

        // For those, who have 'update_plugins' rights - update_plugins are official WordPress role for updates
        add_menu_page(
            $this->lang->getText('LANG_MENU_DEALS_TEXT'), $this->lang->getText('LANG_MENU_DEALS_TEXT'),
            "update_plugins", "{$urlPrefix}single-menu", array($this, "printSingleStatus"), $iconURL, $validMenuPosition
        );
        add_submenu_page(
            "{$urlPrefix}single-menu", $this->lang->getText('LANG_STATUS_TEXT'), $this->lang->getText('LANG_STATUS_TEXT'),
            "update_plugins", "{$urlPrefix}single-status", array($this, "printSingleStatus")
        );
        remove_submenu_page("{$urlPrefix}single-menu", "{$urlPrefix}single-menu");
    }

    /**
     * @param int $paramMenuPosition
     */
	public function addRegularMenu($paramMenuPosition = 97)
	{
        $validMenuPosition = intval($paramMenuPosition);
		$iconURL = $this->conf->getRouting()->getAdminImagesURL('Plugin.png');
		$pluginPrefix = $this->conf->getPluginPrefix();
        $urlPrefix = $this->conf->getPluginURL_Prefix();

        // For those, who have 'view_{$pluginPrefix}partner_earnings' rights
        add_menu_page(
            $this->lang->getText('LANG_MENU_DEALS_TEXT'), $this->lang->getText('LANG_MENU_DEALS_TEXT'),
            "view_{$pluginPrefix}all_deals", "{$urlPrefix}single-menu", array($this, "printDealManager"), $iconURL, $validMenuPosition
        );
            // For those, who have 'view_{$pluginPrefix}all_deals' or 'manage_{$pluginPrefix}all_deals' rights
            add_submenu_page(
                "{$urlPrefix}single-menu", $this->lang->getText('LANG_DEAL_MANAGER_TEXT'), $this->lang->getText('LANG_DEAL_MANAGER_TEXT'),
                "view_{$pluginPrefix}all_deals", "{$urlPrefix}deal-manager", array($this, "printDealManager")
            );
                add_submenu_page(
                    "{$urlPrefix}deal-manager", $this->lang->getText('LANG_DEAL_ADD_EDIT_TEXT'), $this->lang->getText('LANG_DEAL_ADD_EDIT_TEXT'),
                    "manage_{$pluginPrefix}all_deals", "{$urlPrefix}add-edit-deal", array($this, "printDealAddEdit")
                );

            // For those, who have 'manage_{$pluginPrefix}all_settings' rights
            add_submenu_page(
                "{$urlPrefix}single-menu", $this->lang->getText('LANG_DEMOS_TEXT'), $this->lang->getText('LANG_DEMOS_TEXT'),
                "manage_{$pluginPrefix}all_settings","{$urlPrefix}demos", array($this, "printDemos")
            );
                add_submenu_page(
                    "{$urlPrefix}demo", $this->lang->getText('LANG_DEMO_IMPORT_TEXT'), $this->lang->getText('LANG_DEMO_IMPORT_TEXT'),
                    "manage_{$pluginPrefix}all_settings","{$urlPrefix}import-demo", array($this, "printImportDemo")
                );

            // For those, who have 'edit_pages' rights
            // We allow to see shortcodes for those who have rights to edit pages (including item description pages)
            add_submenu_page(
                "{$urlPrefix}single-menu", $this->lang->getText('LANG_MANUAL_TEXT'), $this->lang->getText('LANG_MANUAL_TEXT'),
                "edit_pages","{$urlPrefix}manual", array($this, "printManual")
            );

            // For those, who have 'view_{$pluginPrefix}all_settings' or 'manage_{$pluginPrefix}all_settings' rights
            add_submenu_page(
                "{$urlPrefix}single-menu", $this->lang->getText('LANG_SETTINGS_TEXT'), $this->lang->getText('LANG_SETTINGS_TEXT'),
                "view_{$pluginPrefix}all_settings","{$urlPrefix}settings", array($this, "printSettings")
            );
                add_submenu_page(
                    "{$urlPrefix}settings", $this->lang->getText('LANG_SETTINGS_CHANGE_GLOBAL_SETTINGS_TEXT'), $this->lang->getText('LANG_SETTINGS_CHANGE_GLOBAL_SETTINGS_TEXT'),
                    "manage_{$pluginPrefix}all_settings","{$urlPrefix}change-global-settings", array($this, "printChangeGlobalSettings")
                );

            add_submenu_page(
                "{$urlPrefix}single-menu", $this->lang->getText('LANG_STATUS_TEXT'), $this->lang->getText('LANG_STATUS_TEXT'),
                "update_plugins", "{$urlPrefix}single-status", array($this, "printSingleStatus")
            );
            remove_submenu_page("{$urlPrefix}single-menu", "{$urlPrefix}single-menu");
    }


    /* ------------------------------------------------------------------------------------- */
    /* ------- MENU IMPLEMENTATION METHODS ------------------------------------------------- */
    /* ------------------------------------------------------------------------------------- */

    // Deal Manager
    public function printDealManager()
    {
        try
        {
            $objDealController = new DealController($this->conf, $this->lang);
            $objDealController->printContent();
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }

    public function printDealAddEdit()
    {
        try
        {
            $objAddEditController = new AddEditDealController($this->conf, $this->lang);
            $objAddEditController->printContent();
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }


    // Demos
    public function printDemos()
    {
        try
        {
            $objDemosController = new DemosController($this->conf, $this->lang);
            $objDemosController->printContent();
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }

    public function printImportDemo()
    {
        try
        {
            $objImportDemoController = new ImportDemoController($this->conf, $this->lang);
            $objImportDemoController->printContent();
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }


    // Manual
    public function printManual()
    {
        try
        {
            $objManualController = new ManualController($this->conf, $this->lang);
            $objManualController->printContent();
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }


    // Settings
    public function printSettings()
    {
        try
        {
            $objSettingsController = new SettingsController($this->conf, $this->lang);
            $objSettingsController->printContent();
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }

    public function printChangeGlobalSettings()
    {
        try
        {
            $objAddEditController = new ChangeGlobalSettingsController($this->conf, $this->lang);
            $objAddEditController->printContent();
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }


    // Single Status
	public function printSingleStatus()
	{
        try
        {
            $objStatusController = new SingleController($this->conf, $this->lang);
            $objStatusController->printContent();
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}


	/******************************************************************************************/
	/* Other methods                                                                          */
	/******************************************************************************************/
    /**
     * @param $paramName
     * @param $paramErrorMessage
     */
    private function processError($paramName, $paramErrorMessage)
    {
        if(StaticValidator::inWP_Debug())
        {
            $sanitizedName = sanitize_text_field($paramName);
            $sanitizedErrorMessage = sanitize_text_field($paramErrorMessage);
            // Load errors only in local or global debug mode
            $this->errorMessages[] = sprintf($this->lang->getText('LANG_ERROR_IN_METHOD_TEXT'), $sanitizedName, $sanitizedErrorMessage);

            // 'add_action('admin_notices', ...)' doesn't work here (maybe due to fact, that 'admin_notices' has to be registered not later than X point in code)

            // Works
            $errorMessageHTML = '<div id="message" class="error"><p>'.esc_br_html($sanitizedErrorMessage).'</p></div>';

            // Based on WP Coding Standards ticket #340, the WordPress '_doing_it_wrong' method does not escapes the HTML by default,
            // so this has to be done by us. Read more: https://github.com/WordPress/WordPress-Coding-Standards/pull/340
            _doing_it_wrong(esc_html($sanitizedName), $errorMessageHTML, $this->conf->getPluginSemver());
        }
    }
}