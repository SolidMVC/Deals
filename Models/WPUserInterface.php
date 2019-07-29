<?php
/**
 * WP User (Element) must-have interface (without settings array) - must have a single WP User Id
 * Interface purpose is describe all public methods used available in the class and enforce to use them
 * @package Deals
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Deals\Models;
use Deals\Models\Configuration\ConfigurationInterface;
use Deals\Models\Language\LanguageInterface;

interface WPUserInterface {

    /**
     * @param ConfigurationInterface $paramConf
     * @param LanguageInterface $paramLang
     * @param int $paramWPUserId
     */
    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang, $paramWPUserId);

    /**
     * @return int
     */
    public function getId();

    /**
     * Debug status
     * @return bool
     */
    public function inDebug();

    /**
     * @return string
     */
    public function getDisplayName();
}