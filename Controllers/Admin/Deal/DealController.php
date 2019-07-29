<?php
/**
 * @package Deals
 * @note Variables prefixed with 'local' are not used in templates
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Deals\Controllers\Admin\Deal;
use Deals\Controllers\Admin\AbstractController;
use Deals\Models\Configuration\ConfigurationInterface;
use Deals\Models\Deal\DealsObserver;
use Deals\Models\Formatting\StaticFormatter;
use Deals\Models\Language\LanguageInterface;

final class DealController extends AbstractController
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
        // Create mandatory instances
        $objDealsObserver = new DealsObserver($this->conf, $this->lang, $this->dbSets->getAll());

        // 1. Set the view variables - Tabs
        $this->view->tabs = StaticFormatter::getTabParams(
            array('deals'), 'deals', isset($_GET['tab']) ? $_GET['tab'] : ''
        );

        // 2. Set the view variables - deals tab
        $this->view->addNewDealURL = admin_url('admin.php?page='.$this->conf->getPluginURL_Prefix().'add-edit-deal&deal_id=0');
        $this->view->trustedAdminDealListHTML = $objDealsObserver->getTrustedAdminListHTML();

        // Print the template
        $templateRelPathAndFileName = 'Deal'.DIRECTORY_SEPARATOR.'ManagerTabs.php';
        echo $this->view->render($this->conf->getRouting()->getAdminTemplatesPath($templateRelPathAndFileName));
    }
}
