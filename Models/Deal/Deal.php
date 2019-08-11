<?php
/**
 * Deal Element
 *
 * NOTE #1: There should be no deals pages. Also there should be no target_url as well. Only deal description will be shown on deal image,
 *       if description exists. Reason - We don’t want to make the system cumbersome, and these features would make it.
 * NOTE #2: Deal slider is enough to display deals, we don’t need a separate checkbox for display_in_list,
 *          as we don’t ever plan to have a separate page for deals nor we ever plan to allow partners to have deals,
 *          so we don’t need such cumbersome management process then.
 *
 * @package Deals
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Deals\Models\Deal;
use Deals\Models\AbstractStack;
use Deals\Models\Configuration\ConfigurationInterface;
use Deals\Models\ElementInterface;
use Deals\Models\File\StaticFile;
use Deals\Models\Formatting\StaticFormatter;
use Deals\Models\StackInterface;
use Deals\Models\Validation\StaticValidator;
use Deals\Models\Language\LanguageInterface;

final class Deal extends AbstractStack implements StackInterface, ElementInterface
{
    private $conf 	                = NULL;
    private $lang 		            = NULL;
    private $debugMode 	            = 0;
    private $dealId                 = 0;
    private $shortTitleMaxLength	= 15;
    private $thumbWidth	            = 493;
    private $thumbHeight		    = 311;

    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang, array $paramSettings, $paramDealId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitization will kill the system speed
        $this->lang = $paramLang;
        $this->dealId = StaticValidator::getValidValue($paramDealId, 'positive_integer', 0);

        if(isset($paramSettings['conf_short_deal_title_max_length']))
        {
            // Set short title max length
            $this->shortTitleMaxLength = StaticValidator::getValidPositiveInteger($paramSettings['conf_short_deal_title_max_length'], 15);
        }

        if(isset($paramSettings['conf_deal_thumb_w'], $paramSettings['conf_deal_thumb_h']))
        {
            // Set image dimensions
            $this->thumbWidth = StaticValidator::getValidPositiveInteger($paramSettings['conf_deal_thumb_w'], 493);
            $this->thumbHeight = StaticValidator::getValidPositiveInteger($paramSettings['conf_deal_thumb_h'], 311);
        }
    }

    private function getDataFromDatabaseById($paramDealId, $paramColumns = array('*'))
    {
        $validDealId = StaticValidator::getValidPositiveInteger($paramDealId, 0);
        $validSelect = StaticValidator::getValidSelect($paramColumns);

        $sqlQuery = "
            SELECT {$validSelect}
            FROM {$this->conf->getPrefix()}deals
            WHERE deal_id='{$validDealId}'
        ";
        $retData = $this->conf->getInternalWPDB()->get_row($sqlQuery, ARRAY_A);

        return $retData;
    }

    public function getId()
    {
        return $this->dealId;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    /**
     * @note Do not translate title here - it is used for editing
     * @param bool $paramIncludeUnclassified - not used
     * @return mixed
     */
    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        $ret = $this->getDataFromDatabaseById($this->dealId);
        if(!is_null($ret))
        {
            // Make raw
            $ret['deal_title'] = stripslashes($ret['deal_title']);
            $ret['deal_image'] = stripslashes($ret['deal_image']);
            $ret['target_url'] = stripslashes($ret['target_url']);
            $ret['deal_description'] = stripslashes($ret['deal_description']);

            // Retrieve translation
            $ret['translated_deal_title'] = $this->lang->getTranslated("de{$ret['deal_id']}_deal_title", $ret['deal_title']);
            $ret['translated_short_deal_title'] = StaticFormatter::getTruncated($ret['translated_deal_title'], $this->shortTitleMaxLength);
            $ret['translated_deal_description'] = $this->lang->getTranslated("de{$ret['deal_id']}_deal_description", $ret['deal_description']);

            // Note: providing exact file name is important here, because then the system will correctly decide
            //       from which exact folder to load that file, as not all demo images may be overridden by the theme
            if($ret['demo_deal_image'] == 1)
            {
                $imageFolder = $this->conf->getRouting()->getDemoGalleryURL($ret['deal_image'], FALSE);
            } else
            {
                $imageFolder = $this->conf->getGlobalGalleryURL();
            }

            // Extend with additional rows
            $ret['short_deal_title'] = StaticFormatter::getTruncated($ret['deal_title'], $this->shortTitleMaxLength);
            $ret['deal_thumb_url'] = $ret['deal_image'] != "" ? $imageFolder."thumb_".$ret['deal_image'] : "";
            $ret['deal_image_url'] = $ret['deal_image'] != "" ? $imageFolder.$ret['deal_image'] : "";

            $targetLinkHTML = $ret['target_url'] != '' ? '<a href="'.esc_attr($ret['target_url']).'" target="_blank">' : '';
            $targetLinkHTML .= '<span class="deal-target">'.$ret['translated_deal_title'].'</span>';
            $targetLinkHTML .= $ret['target_url'] != '' ? '</a>' : '';
            $ret['target_link_html'] = $targetLinkHTML;
        }

        return $ret;
    }

    /**
     * @param array $params
     * @return false|int
     */
    public function save(array $params)
    {
        $saved = FALSE;
        $ok = TRUE;
        $validDealId = StaticValidator::getValidPositiveInteger($this->dealId, 0);
        $validDealTitle = isset($params['deal_title']) ? esc_sql(sanitize_text_field($params['deal_title'])) : ''; // for sql query only
        $validTargetURL = isset($params['target_url']) ? esc_url_raw($params['target_url']) : ''; // for sql query only
        $validDealDescription = isset($params['deal_description']) ? esc_sql(implode("\n", array_map('sanitize_text_field', explode("\n", $params['deal_description'])))) : ''; // for sql query only
        $validDealEnabled = isset($params['deal_enabled']) ? 1 : 0;

        if(isset($params['deal_order']) && StaticValidator::isPositiveInteger($params['deal_order']))
        {
            $validDealOrder = StaticValidator::getValidPositiveInteger($params['deal_order'], 1);
        } else
        {
            // SELECT MAX
            $sqlQuery = "
                SELECT MAX(deal_order) AS max_order
                FROM {$this->conf->getPrefix()}deals
                WHERE 1
            ";
            $maxOrderResult = $this->conf->getInternalWPDB()->get_var($sqlQuery);
            $validDealOrder = !is_null($maxOrderResult) ? intval($maxOrderResult)+1 : 1;
        }

        if($validDealTitle == "")
        {
            // Deal title is required
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('LANG_DEAL_TITLE_REQUIRED_ERROR_TEXT');
        }

        // Search for existing deal title
        $dealTitleExistsQuery = "
            SELECT deal_id
            FROM {$this->conf->getPrefix()}deals
            WHERE deal_id!={$validDealId} AND deal_title='{$validDealTitle}'
            AND blog_id='{$this->conf->getBlogId()}'
        ";
        $dealTitleExists = $this->conf->getInternalWPDB()->get_var($dealTitleExistsQuery);
        if(!is_null($dealTitleExists))
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('LANG_DEAL_TITLE_EXISTS_ERROR_TEXT');
        }

        if($validDealId > 0 && $ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                UPDATE {$this->conf->getPrefix()}deals SET
                deal_title='{$validDealTitle}', target_url='{$validTargetURL}',
                deal_description='{$validDealDescription}',
                deal_enabled='{$validDealEnabled}', deal_order='{$validDealOrder}'
                WHERE deal_id='{$validDealId}' AND blog_id='{$this->conf->getBlogId()}'
            ");

            // Only if there is error in query we will skip that, if no changes were made (and 0 was returned) we will still process
            if($saved !== FALSE)
            {
                $dealEditData = $this->conf->getInternalWPDB()->get_row("
                    SELECT *
                    FROM {$this->conf->getPrefix()}deals
                    WHERE deal_id='{$validDealId}' AND blog_id='{$this->conf->getBlogId()}'
                ", ARRAY_A);

                // Upload image
                if(
                    isset($params['delete_deal_image']) && $dealEditData['deal_image'] != "" &&
                    $dealEditData['demo_deal_image'] == 0
                ) {
                    // Unlink files only if it's not a demo image
                    unlink($this->conf->getGlobalGalleryPath().$dealEditData['deal_image']);
                    unlink($this->conf->getGlobalGalleryPath()."thumb_".$dealEditData['deal_image']);
                }

                $validUploadedImageFileName = '';
                if($_FILES['deal_image']['tmp_name'] != '')
                {
                    $uploadedImageFileName = StaticFile::uploadImageFile($_FILES['deal_image'], $this->conf->getGlobalGalleryPathWithoutEndSlash(), "deal_");
                    StaticFile::makeThumbnail($this->conf->getGlobalGalleryPath(), $uploadedImageFileName, $this->thumbWidth, $this->thumbHeight, "thumb_");
                    $validUploadedImageFileName = esc_sql(sanitize_file_name($uploadedImageFileName)); // for sql query only
                }

                if($validUploadedImageFileName != '' || isset($params['delete_deal_image']))
                {
                    // Update the sql
                    $this->conf->getInternalWPDB()->query("
                        UPDATE {$this->conf->getPrefix()}deals SET
                        deal_image='{$validUploadedImageFileName}', demo_deal_image='0'
                        WHERE deal_id='{$validDealId}' AND blog_id='{$this->conf->getBlogId()}'
                    ");
                }
            }

            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('LANG_DEAL_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('LANG_DEAL_UPDATED_TEXT');
            }
        } else if($ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                INSERT INTO {$this->conf->getPrefix()}deals
                (
                    deal_title, target_url,
                    deal_description, deal_enabled,
                    deal_order, blog_id
                ) VALUES
                (
                    '{$validDealTitle}', '{$validTargetURL}',
                    '{$validDealDescription}', '{$validDealEnabled}',
                    '{$validDealOrder}', '{$this->conf->getBlogId()}'
                )
            ");

            // We will process only if there one line was added to sql
            if($saved)
            {
                // Get newly inserted deal id
                $validInsertedNewDealId = $this->conf->getInternalWPDB()->insert_id;

                // Update the core deal id for future use
                $this->dealId = $validInsertedNewDealId;

                $validUploadedImageFileName = '';
                if($_FILES['deal_image']['tmp_name'] != '')
                {
                    $uploadedImageFileName = StaticFile::uploadImageFile($_FILES['deal_image'], $this->conf->getGlobalGalleryPathWithoutEndSlash(), "deal_");
                    StaticFile::makeThumbnail($this->conf->getGlobalGalleryPath(), $uploadedImageFileName, $this->thumbWidth, $this->thumbHeight, "thumb_");
                    $validUploadedImageFileName = esc_sql(sanitize_file_name($uploadedImageFileName)); // for sql query only
                }

                if($validUploadedImageFileName != '')
                {
                    // Update the sql
                    $this->conf->getInternalWPDB()->query("
                        UPDATE {$this->conf->getPrefix()}deals SET
                        deal_image='{$validUploadedImageFileName}', demo_deal_image='0'
                        WHERE deal_id='{$validInsertedNewDealId}' AND blog_id='{$this->conf->getBlogId()}'
                    ");
                }
            }

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('LANG_DEAL_INSERTION_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('LANG_DEAL_INSERTED_TEXT');
            }
        }

        return $saved;
    }

    public function registerForTranslation()
    {
        $dealDetails = $this->getDetails();
        if(!is_null($dealDetails))
        {
            $this->lang->register("de{$this->dealId}_deal_title", $dealDetails['deal_title']);
            $this->lang->register("de{$this->dealId}_deal_description", $dealDetails['deal_description']);
            $this->okayMessages[] = $this->lang->getText('LANG_DEAL_REGISTERED_TEXT');
        }
    }

    /**
     * @return false|int
     */
    public function delete()
    {
        $deleted = FALSE;
        $dealDetails = $this->getDetails();
        if(!is_null($dealDetails))
        {
            $deleted = $this->conf->getInternalWPDB()->query("
                DELETE FROM {$this->conf->getPrefix()}deals
                WHERE deal_id='{$dealDetails['deal_id']}' AND blog_id='{$this->conf->getBlogId()}'
            ");

            if($deleted)
            {
                // Unlink image file
                if($dealDetails['demo_deal_image'] == 0 && $dealDetails['deal_image'] != "")
                {
                    unlink($this->conf->getGlobalGalleryPath().$dealDetails['deal_image']);
                    unlink($this->conf->getGlobalGalleryPath()."thumb_".$dealDetails['deal_image']);
                }
            }
        }

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('LANG_DEAL_DELETION_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('LANG_DEAL_DELETED_TEXT');
        }

        return $deleted;
    }
}