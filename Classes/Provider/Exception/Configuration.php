<?php
namespace Innologi\TYPO3AssetProvider\Provider\Exception;

/**
 * Configuration Exception
 *
 * @package TYPO3AssetProvider
 * @author Frenck Lutke
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 2 or later
 */
class Configuration extends ProviderException
{

    /**
     * Set message
     *
     * @param string $message
     * @return void
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}
