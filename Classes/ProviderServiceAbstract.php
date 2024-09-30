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

    public function __construct(
        protected readonly ConfigurationManagerInterface $configurationManager,
    ) {
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

        $frameworkConfiguration = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        );
        if (isset($frameworkConfiguration['assets'])) {
            $this->configuration = array_merge(
                $this->configuration,
                $frameworkConfiguration['assets']
            );
        }

        // inline configurations require the original TS
        $originalTypoScript = $this->configurationManager->getConfiguration(
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
