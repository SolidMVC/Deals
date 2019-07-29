<?php
/**
 * @package Deals
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Deals\Controllers\Front\Shortcodes;
use Deals\Controllers\Front\AbstractController;
use Deals\Models\Deal\Deal;
use Deals\Models\Deal\DealsObserver;
use Deals\Models\Configuration\ConfigurationInterface;
use Deals\Models\Language\LanguageInterface;

final class DealsController extends AbstractController
{
    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang, $paramArrLimits = array())
    {
        parent::__construct($paramConf, $paramLang, $paramArrLimits);
    }

    /**
     * @param string $paramLayout
     * @param string $paramStyle
     * @return string
     * @throws \Exception
     */
    public function getContent($paramLayout = "List", $paramStyle = "")
    {
        // Create mandatory instances
        $objDealsObserver = new DealsObserver($this->conf, $this->lang, $this->dbSets->getAll());

        $dealIds = $objDealsObserver->getEnabledIds($this->deal);
        $dealSlides = array();
        $deals = array();
        $i = 1;
        $totalDeals = sizeof($dealIds);
        foreach($dealIds AS $dealId)
        {
            $currentSlide = absint(ceil($i / 3)); // Make sure slide is integer
            $objDeal = new Deal($this->conf, $this->lang, $this->dbSets->getAll(), $dealId);
            $dealDetails = $objDeal->getDetails();
            if(($i+1) % 3 == 0)
            {
                // Pre-select the 2nd deal on each slide by default
                $dealDetails['selected'] = TRUE;
            } else if((($i+2) % 3 == 0) && ($i == $totalDeals))
            {
                // If this is the last slide and it is only 1 deal on it - pre-select it.
                $dealDetails['selected'] = FALSE;
            } else
            {
                $dealDetails['selected'] = FALSE;
            }
            if(!isset($dealSlides[$currentSlide]))
            {
                $dealSlides[$currentSlide] = array();
            }
            $dealSlides[$currentSlide][] = $dealDetails;
            $deals[] = $dealDetails;
            $i++;
        }

        // Get the template
        $this->view->dealSlides = $dealSlides;
        $this->view->deals = $deals;
        $retContent = $this->getTemplate('', 'Deals', $paramLayout, $paramStyle);

        return $retContent;
    }
}