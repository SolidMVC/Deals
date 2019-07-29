<?php
/**
 * Element must-have interface - must have a single element Id
 * Interface purpose is describe all public methods used available in the class and enforce to use them
 * @package Deals
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Deals\Models;
use Deals\Models\Configuration\ConfigurationInterface;
use Deals\Models\Language\LanguageInterface;

interface ElementInterface
{
    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang, array $paramSettings, $paramElementId);
    public function getId();
    public function inDebug();
    public function getDetails($paramIncludeUnclassified = FALSE);
    /**
     * @param array $params
     * @return false|int
     */
    public function save(array $params);
    /**
     * @return void
     */
    public function registerForTranslation();
    /**
     * @return false|int
     */
    public function delete();
}