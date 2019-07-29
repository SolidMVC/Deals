<?php
/**
 * Database Table Structure

 * @package Deals
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Deals\Models\Deal;
use Deals\Models\AbstractTable;
use Deals\Models\Configuration\ConfigurationInterface;
use Deals\Models\Language\LanguageInterface;
use Deals\Models\TableInterface;
use Deals\Models\Validation\StaticValidator;

final class DealsTable extends AbstractTable implements TableInterface
{
    /**
     * @param ConfigurationInterface $paramConf
     * @param LanguageInterface $paramLang
     * @param int $paramBlogId
     */
    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang, $paramBlogId)
    {
        parent::__construct($paramConf, $paramLang, $paramConf->getPrefix(), "deals", $paramBlogId);
    }

    /**
     * @return bool
     */
    public function create()
    {
        $validTablePrefix = esc_sql(sanitize_text_field($this->tablePrefix)); // for sql queries only
        $validTableName = esc_sql(sanitize_text_field($this->tableName)); // for sql queries only
        $sqlQuery = "CREATE TABLE `{$validTablePrefix}{$validTableName}` (
          `deal_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `deal_title` varchar(100) NOT NULL,
          `deal_image` varchar(255) NOT NULL,
          `demo_deal_image` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `target_url` varchar(255) NOT NULL,
          `deal_description` text NOT NULL,
          `deal_enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `deal_order` int(11) unsigned NOT NULL DEFAULT '0',
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`deal_id`),
          KEY `deal_title` (`deal_title`),
          KEY `deal_enabled` (`deal_enabled`),
          KEY `deal_order` (`deal_order`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$this->conf->getInternalWPDB()->get_charset_collate()};";

        $created = $this->executeQuery($sqlQuery);

        return $created;
    }

    /**
     * @return bool
     */
    public function drop()
    {
        $validTablePrefix = esc_sql(sanitize_text_field($this->tablePrefix)); // for sql queries only
        $validTableName = esc_sql(sanitize_text_field($this->tableName)); // for sql queries only
        $sqlQuery = "DROP TABLE IF EXISTS `{$validTablePrefix}{$validTableName}`;";

        $dropped = $this->executeQuery($sqlQuery);

        return $dropped;
    }

    /**
     * @return bool
     */
    public function deleteContent()
    {
        $validTablePrefix = esc_sql(sanitize_text_field($this->tablePrefix)); // for sql queries only
        $validTableName = esc_sql(sanitize_text_field($this->tableName)); // for sql queries only
        $validBlogId = StaticValidator::getValidPositiveInteger($this->blogId);
        $sqlQuery = "DELETE FROM `{$validTablePrefix}{$validTableName}`
            WHERE blog_id='{$validBlogId}'";

        $deleted = $this->executeQuery($sqlQuery);

        return $deleted;
    }
}