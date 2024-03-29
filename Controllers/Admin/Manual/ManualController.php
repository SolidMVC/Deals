<?php
/**
 * @package Deals
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Deals\Controllers\Admin\Manual;
use Deals\Models\Configuration\ConfigurationInterface;
use Deals\Models\Formatting\StaticFormatter;
use Deals\Models\Language\LanguageInterface;
use Deals\Controllers\Admin\AbstractController;

final class ManualController extends AbstractController
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
        // 1. Set the view variables - Tabs
        $this->view->tabs = StaticFormatter::getTabParams(array(
            'instructions', 'shortcodes', 'shortcode-parameters', 'url-parameters-hashtags', 'ui-overriding'
        ), 'instructions', isset($_GET['tab']) ? $_GET['tab'] : '');

        // Print the template
        $templateRelPathAndFileName = 'Manual'.DIRECTORY_SEPARATOR.'Tabs.php';
        echo $this->view->render($this->conf->getRouting()->getAdminTemplatesPath($templateRelPathAndFileName));
    }
}
