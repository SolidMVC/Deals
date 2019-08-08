<?php
/**
 * @package Deals
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Deals\Controllers\Admin\Deal;
use Deals\Models\Configuration\ConfigurationInterface;
use Deals\Models\Deal\Deal;
use Deals\Models\Language\LanguageInterface;
use Deals\Controllers\Admin\AbstractController;
use Deals\Models\Cache\StaticSession;

final class AddEditDealController extends AbstractController
{
    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramDealId)
    {
        $objDeal = new Deal($this->conf, $this->lang, $this->dbSets->getAll(), $paramDealId);
        $objDeal->delete();

        StaticSession::cacheHTML_Array('admin_debug_html', $objDeal->getDebugMessages());
        StaticSession::cacheValueArray('admin_okay_message', $objDeal->getOkayMessages());
        StaticSession::cacheValueArray('admin_error_message', $objDeal->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getPluginURL_Prefix().'deal-manager&tab=deals');
        exit;
    }

    private function processSave($paramDealId)
    {
        // Create mandatory instances
        $objDeal = new Deal($this->conf, $this->lang, $this->dbSets->getAll(), $paramDealId);

        $saved = $objDeal->save($_POST);
        if($saved && $this->lang->canTranslateSQL())
        {
            $objDeal->registerForTranslation();
        }

        StaticSession::cacheHTML_Array('admin_debug_html', $objDeal->getDebugMessages());
        StaticSession::cacheValueArray('admin_okay_message', $objDeal->getOkayMessages());
        StaticSession::cacheValueArray('admin_error_message', $objDeal->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getPluginURL_Prefix().'deal-manager&tab=deals');
        exit;
    }

    /**
     * @throws \Exception
     * @return void
     */
    public function printContent()
    {
        // Process actions
        if(isset($_GET['delete_deal'])) { $this->processDelete($_GET['delete_deal']); }
        if(isset($_POST['save_deal'], $_POST['deal_id'])) { $this->processSave($_POST['deal_id']); }

        $paramDealId = isset($_GET['deal_id']) ? $_GET['deal_id'] : 0;
        $objDeal = new Deal($this->conf, $this->lang, $this->dbSets->getAll(), $paramDealId);
        $localDetails = $objDeal->getDetails();

        // Set the view variables
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getPluginURL_Prefix().'deal-manager&tab=deals');
        $this->view->formAction = admin_url('admin.php?page='.$this->conf->getPluginURL_Prefix().'add-edit-deal&noheader=true');
        if(!is_null($localDetails))
        {
            $this->view->dealId = $localDetails['deal_id'];
            $this->view->dealTitle = $localDetails['deal_title'];
            $this->view->dealImageURL = $localDetails['deal_image_url'];
            $this->view->demoDealImage = $localDetails['demo_deal_image'];
            $this->view->targetURL = $localDetails['target_url'];
            $this->view->dealDescription = $localDetails['deal_description'];
            $this->view->dealEnabled = $localDetails['deal_enabled'] == 1;
            $this->view->dealOrder = $localDetails['deal_order'];
        } else
        {
            $this->view->dealId = 0;
            $this->view->dealTitle = '';
            $this->view->dealImageURL = '';
            $this->view->demoDealImage = 0;
            $this->view->targetURL = '';
            $this->view->dealDescription = '';
            $this->view->dealEnabled = TRUE; // Default is TRUE
            $this->view->dealOrder = '';
        }

        // Print the template
        $templateRelPathAndFileName = 'Deal'.DIRECTORY_SEPARATOR.'AddEditDealForm.php';
        echo $this->view->render($this->conf->getRouting()->getAdminTemplatesPath($templateRelPathAndFileName));
    }
}
