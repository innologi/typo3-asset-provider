<?php
namespace Innologi\TYPO3AssetProvider;

use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Core\SingletonInterface;

/**
 * TYPO3 Extbase Asset Provider Service Abstract
 *
 * @package TYPO3AssetProvider
 * @author Frenck Lutke
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 2 or later
 */
abstract class ProviderServiceAbstract implements ProviderServiceInterface, SingletonInterface
{

    /**
     *
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Asset-loading configuration
     *
     * @var array
     */
    protected $configuration;

    /**
     * Asset-loading typoscript
     *
     * @var array
     */
    protected $typoscript;

    /**
     * Injects the object manager
     *
     * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
     */
    public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Initializes the configuration
     *
     * @param string $extensionKey
     * @return void
     */
    protected function initializeConfiguration(string $extensionKey): void
    {
        $this->configuration = [
            'default' => []
        ];
        $this->typoscript = [
            'default.' => []
        ];

        /** @var ConfigurationManagerInterface $configurationManager */
        $configurationManager = $this->objectManager->get(ConfigurationManagerInterface::class);
        $frameworkConfiguration = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        );
        if (isset($frameworkConfiguration['assets'])) {
            $this->configuration = array_merge(
                $this->configuration,
                $frameworkConfiguration['assets']
            );
        }

        // inline configurations require the original TS
        $originalTypoScript = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );
        if (isset($originalTypoScript['plugin.']['tx_' . $extensionKey . '.']['assets.'])) {
            $this->typoscript = array_merge(
                $this->typoscript,
                $originalTypoScript['plugin.']['tx_' . $extensionKey . '.']['assets.']
            );
        }
    }
}
