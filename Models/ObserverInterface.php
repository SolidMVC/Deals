<?php
/**
 * Observer must-have interface
 * Interface purpose is describe all public methods used available in the class and enforce to use them
 * @package Deals
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Deals\Models;
use Deals\Models\Configuration\ConfigurationInterface;
use Deals\Models\Language\LanguageInterface;

interface ObserverInterface
{
    // Constructor
    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang, array $paramSettings);
    public function inDebug();
}