<?php
/**
 * @package Deals
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Deals\Controllers\Admin\Settings;
use Deals\Models\Configuration\ConfigurationInterface;
use Deals\Models\Formatting\StaticFormatter;
use Deals\Models\Country\CountriesObserver;
use Deals\Models\Notification\PhoneNotificationsObserver;
use Deals\Models\Style\StylesObserver;
use Deals\Models\Language\LanguageInterface;
use Deals\Controllers\Admin\AbstractController;

final class SettingsController extends AbstractController
{
    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    /**
     * @throws \Exception
     * @return void
     */
    public function printContent()
    {
        // Tab - global settings
        $objStylesObserver = new StylesObserver($this->conf, $this->lang, $this->dbSets->getAll());
        $this->view->globalSettingsTabFormAction = admin_url('admin.php?page='.$this->conf->getPluginURL_Prefix().'change-global-settings&noheader=true');
        $this->view->trustedSystemStylesDropdownOptionsHTML = $objStylesObserver->getTrustedDropdownOptionsHTML($this->dbSets->get('conf_system_style'));
        $this->view->arrGlobalSettings = (new ChangeGlobalSettingsController($this->conf, $this->lang))->getSettings();

        // Set the view variables - Tabs
        $this->view->tabs = StaticFormatter::getTabParams(array(
            'global-settings'
        ), 'global-settings', isset($_GET['tab']) ? $_GET['tab'] : '');


        // Print the template
        $templateRelPathAndFileName = 'Settings'.DIRECTORY_SEPARATOR.'Tabs.php';
        echo $this->view->render($this->conf->getRouting()->getAdminTemplatesPath($templateRelPathAndFileName));
    }
}
