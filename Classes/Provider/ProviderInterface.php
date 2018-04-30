<?php
namespace Innologi\TYPO3AssetProvider\Provider;

/**
 * Asset Provider Interface
 *
 * @package TYPO3AssetProvider
 * @author Frenck Lutke
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 2 or later
 */
interface ProviderInterface
{

    /**
     * Processes configuration of asset type
     *
     * @param array $configuration
     * @param array $typoscript
     * @return void
     */
    public function processConfiguration(array $configuration, array $typoscript): void;

    /**
     * Add Library Asset
     *
     * @param array $conf
     * @param string $id
     * @return void
     */
    public function addLibrary(array $conf, string $id = ''): void;

    /**
     * Add File Asset
     *
     * @param array $conf
     * @param string $id
     * @return void
     */
    public function addFile(array $conf, string $id = ''): void;

    /**
     * Add Inline Asset
     *
     * @param string $inline
     * @param array $conf
     * @param string $id
     * @return void
     */
    public function addInline(string $inline, array $conf, string $id = ''): void;
}
