<?php
namespace Innologi\TYPO3AssetProvider;

/**
 * TYPO3 Extbase Asset Provider Service Interface
 *
 * @package TYPO3AssetProvider
 * @author Frenck Lutke
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 2 or later
 */
interface ProviderServiceInterface
{

    /**
     * Provide assets based on Extbase arguments
     *
     * @param string $extensionKey
     * @param string $controllerName
     * @param string $actionName
     * @return void
     */
    public function provideAssets(string $extensionKey, string $controllerName, string $actionName): void;
}
