<?php
/**
 * Deals Observer

 * @package Deals
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Deals\Models\Deal;
use Deals\Models\Configuration\ConfigurationInterface;
use Deals\Models\ObserverInterface;
use Deals\Models\Language\LanguageInterface;
use Deals\Models\Validation\StaticValidator;

final class DealsObserver implements ObserverInterface
{
    private $conf 	                = NULL;
    private $lang 		            = NULL;
    private $settings		        = array();
    private $debugMode 	            = 0;

    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang, array $paramSettings)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitization will kill the system speed
        $this->lang = $paramLang;
        // Set saved settings
        $this->settings = $paramSettings;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getEnabledIds($paramDealId = -1)
    {
        return $this->getAllIds($paramDealId, "ENABLED");
    }

    public function getAllIds($paramDealId = -1, $paramStatus = "ANY")
    {
        $validDealId = StaticValidator::getValidInteger($paramDealId, -1); // -1 means 'skip'

        $sqlAdd = '';
        if($validDealId > 0)
        {
            // Deal id
            $sqlAdd .= " AND deal_id='{$validDealId}'";
        }

        if(in_array($paramStatus, array("ENABLED", "DISABLED")))
        {
            // Deal enabled
            $validDealEnabled = $paramStatus == "ENABLED" ? 1 : 0;
            $sqlAdd .= " AND deal_enabled='{$validDealEnabled}'";
        }

        $searchSQL = "
            SELECT deal_id
            FROM {$this->conf->getPrefix()}deals
            WHERE blog_id='{$this->conf->getBlogId()}'{$sqlAdd}
            ORDER BY deal_order ASC, deal_id ASC
		";

        //DEBUG
        //echo nl2br($searchSQL)."<br /><br />";

        $searchResult = $this->conf->getInternalWPDB()->get_col($searchSQL);

        return $searchResult;
    }

    public function getTranslatedDropdownOptionsHTML($paramSelectedDealId = -1, $paramDefaultValue = -1, $paramDefaultLabel = "")
    {
        return $this->getTrustedDropdownOptionsHTML($paramSelectedDealId, $paramDefaultValue, $paramDefaultLabel, TRUE);
    }

    /**
     * @param int $paramSelectedDealId
     * @param int $paramDefaultValue
     * @param string $paramDefaultLabel
     * @param bool $paramTranslated
     * @return string
     */
    public function getTrustedDropdownOptionsHTML($paramSelectedDealId = -1, $paramDefaultValue = -1, $paramDefaultLabel = "", $paramTranslated = FALSE)
    {
        $validDefaultValue = StaticValidator::getValidInteger($paramDefaultValue, -1);
        $sanitizedDefaultLabel = sanitize_text_field($paramDefaultLabel);
        $dealIds = $this->getAllIds(-1, "ANY");

        $retHTML = '';
        if($paramSelectedDealId == $validDefaultValue)
        {
            $retHTML .= '<option value="'.esc_attr($validDefaultValue).'" selected="selected">'.esc_html($sanitizedDefaultLabel).'</option>';
        } else
        {
            $retHTML .= '<option value="'.esc_attr($validDefaultValue).'">'.esc_html($sanitizedDefaultLabel).'</option>';
        }
        foreach($dealIds AS $dealId)
        {
            $objDeal = new Deal($this->conf, $this->lang, $this->settings, $dealId);
            $dealDetails = $objDeal->getDetails();
            $dealTitle = $paramTranslated ? $dealDetails['translated_deal_title'] : $dealDetails['deal_title'];

            if($dealDetails['deal_id'] == $paramSelectedDealId)
            {
                $retHTML .= '<option value="'.esc_attr($dealDetails['deal_id']).'" selected="selected">'.esc_html($dealTitle).'</option>';
            } else
            {
                $retHTML .= '<option value="'.esc_attr($dealDetails['deal_id']).'">'.esc_html($dealTitle).'</option>';
            }
        }

        return $retHTML;
    }


    /* --------------------------------------------------------------------------- */
    /* ----------------------- METHODS FOR ADMIN ACCESS ONLY --------------------- */
    /* --------------------------------------------------------------------------- */

    public function getTrustedAdminListHTML()
    {
        $retHTML = '';
        $dealIds = $this->getAllIds(-1, "ANY");
        foreach ($dealIds AS $dealId)
        {
            $objDeal = new Deal($this->conf, $this->lang, $this->settings, $dealId);
            $dealDetails = $objDeal->getDetails();

            // Deal title HTML
            $dealTitleHMTL = '';
            if($dealDetails['target_url'] != '')
            {
                $dealTitleHMTL .= '<a href="'.esc_attr($dealDetails['target_url']).'" target="_blank">';
                $dealTitleHMTL .= '<span class="deal-target">'.esc_html($dealDetails['translated_deal_title']).'</span>';
                $dealTitleHMTL .= '</a>';
            } else
            {
                $dealTitleHMTL .= esc_html($dealDetails['translated_deal_title']);
            }
            if($this->lang->canTranslateSQL())
            {
                $dealTitleHMTL .= '<br />-------------------------------<br />';
                $dealTitleHMTL .= '<span class="not-translated" title="'.$this->lang->escAttr('LANG_WITHOUT_TRANSLATION_TEXT').'">('.esc_html($dealDetails['deal_title']).')</span>';
            }

            // Deal description HTML
            $dealDescriptionHTML = esc_br_html($dealDetails['translated_deal_description']);
            if($this->lang->canTranslateSQL())
            {
                $dealDescriptionHTML .= '<br />-------------------------------<br />';
                $dealDescriptionHTML .= '<span class="not-translated" title="'.$this->lang->escAttr('LANG_WITHOUT_TRANSLATION_TEXT').'">('.esc_br_html($dealDetails['deal_description']).')</span>';
            }

            // HTML
            $retHTML .= '<tr>';
            $retHTML .= '<td>'.esc_html($dealId).'</td>';
            $retHTML .= '<td>'.$dealTitleHMTL.'</td>';
            $retHTML .= '<td>'.$dealDescriptionHTML.'</td>';
            $retHTML .= '<td>'.$this->lang->escHTML($dealDetails['deal_enabled'] == 1 ? 'LANG_AVAILABLE_TEXT' : 'LANG_HIDDEN_TEXT').'</td>';
            $retHTML .= '<td style="text-align: center">'.esc_html($dealDetails['deal_order']).'</td>';
            $retHTML .= '<td align="right">';
            if(current_user_can('manage_'.$this->conf->getPluginPrefix().'all_deals'))
            {
                $retHTML .= '<a href="'.admin_url('admin.php?page='.$this->conf->getPluginURL_Prefix().'add-edit-deal&amp;deal_id='.$dealId).'">'.$this->lang->escHTML('LANG_EDIT_TEXT').'</a>';
                $retHTML .= ' - ';
                $retHTML .= '<a href="javascript:;" onclick="javascript:DealsAdmin.deleteDeal(\''.$dealId.'\')">'.$this->lang->escHTML('LANG_DELETE_TEXT').'</a>';
            } else
            {
                $retHTML .= '--';
            }
            $retHTML .= '</td>';
            $retHTML .= '</tr>';
        }

        return  $retHTML;
    }
}