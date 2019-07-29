<?php
/**
 * Manager Role

 * @note - It does not have settings param in constructor on purpose!
 * @package Deals
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Deals\Models\Administrator;
use Deals\Models\AbstractStack;
use Deals\Models\Configuration\ConfigurationInterface;
use Deals\Models\RoleInterface;
use Deals\Models\StackInterface;
use Deals\Models\Language\LanguageInterface;

final class AdministratorRole extends AbstractStack implements StackInterface, RoleInterface
{
    private $conf           = NULL;
    private $lang 		    = NULL;
    private $debugMode 	    = 0;
    private $roleName       = '';

	public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang)
	{
		// Set class settings
		$this->conf = $paramConf;
		// Already sanitized before in it's constructor. Too much sanitization will kill the system speed
		$this->lang = $paramLang;

        $this->roleName = 'administrator'; // No prefix for role here, as it is official WordPress administrator role name
	}

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    /**
     * @return string
     */
    public function getRoleName()
    {
        return $this->roleName;
    }

    /**
     * @return array
     */
    public function getCapabilities()
    {
        $roleCapabilities = array(
            'read'                                                            => true, // true allows this capability
            'view_'.$this->conf->getPluginPrefix().'all_deals'                => true,
            'manage_'.$this->conf->getPluginPrefix().'all_deals'              => true,
            'view_'.$this->conf->getPluginPrefix().'all_settings'             => true,
            'manage_'.$this->conf->getPluginPrefix().'all_settings'           => true,
        );

        return $roleCapabilities;
    }

    public function add()
    {
        // WordPress administrator role cannot be added.

        return FALSE;
    }

    public function remove()
    {
        // WordPress administrator role cannot be remove.

        return FALSE;
    }

    /**
     * @return void
     */
    public function addCapabilities()
    {
        // Add capabilities to this role
        $objWPRole = get_role($this->roleName);
        $capabilitiesToAdd = $this->getCapabilities();
        foreach($capabilitiesToAdd AS $capability => $grant)
        {
            $objWPRole->add_cap($capability, $grant);
        }
    }

    /**
     * @return void
     */
    public function removeCapabilities()
    {
        // Remove capabilities from this role
        $objWPRole = get_role($this->roleName);
        $capabilitiesToRemove = $this->getCapabilities();
        foreach($capabilitiesToRemove AS $capability => $grant)
        {
            $objWPRole->remove_cap($capability);
        }
    }
}