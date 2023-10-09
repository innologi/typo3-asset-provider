<?php
namespace Innologi\TYPO3AssetProvider;

/**
 * TYPO3 Extbase Asset Provider Service
 *
 * @package TYPO3AssetProvider
 * @author Frenck Lutke
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 2 or later
 */
class ProviderService extends ProviderServiceAbstract
{

    /**
     *
     * @var array
     */
    protected $assetRegister = [];

    /**
     * Provide assets based on Extbase arguments
     *
     * @param string $extensionKey
     * @param string $controllerName
     * @param string $actionName
     * @return void
     */
    public function provideAssets(string $extensionKey, string $controllerName, string $actionName): void
    {
        if (isset($this->assetRegister[$controllerName][$actionName])) {
            // already provided these assets
            return;
        }

        // init if not done before
        if ($this->configuration === null || $this->typoscript === null) {
            $this->initializeConfiguration($extensionKey);
        }

        if (isset($this->configuration['controller'][$controllerName])) {
            $configuration = array_merge(
                $this->configuration['default'],
                $this->configuration['controller'][$controllerName]['default'] ?? [],
                $this->configuration['controller'][$controllerName]['action'][$actionName] ?? []
            );
            $typoscript = array_merge(
                $this->typoscript['default.'],
                $this->typoscript['controller.'][$controllerName . '.']['default.'] ?? [],
                $this->typoscript['controller.'][$controllerName . '.']['action.'][$actionName . '.'] ?? []
            );
        } else {
            $configuration = $this->configuration['default'];
            $typoscript = $this->typoscript['default.'];
        }

        $this->runAssetProviders($configuration, $typoscript);

        if (! isset($this->assetRegister[$controllerName])) {
            $this->assetRegister[$controllerName] = [];
        }
        $this->assetRegister[$controllerName][$actionName] = true;
    }

    /**
     * Processes configuration on available asset providers
     *
     * @param array $configuration
     * @param array $typoscript
     * @return void
     */
    protected function runAssetProviders(array $configuration, array $typoscript): void
    {
        foreach ($configuration as $type => $conf) {
            // e.g. JavascriptProvider, CssProvider
            $className = ucfirst($type) . 'Provider';
            /** @var Provider\ProviderInterface $assetProvider */
            $assetProvider = $this->objectManager->get(__NAMESPACE__ . '\\Provider\\' . $className);
            $assetProvider->processConfiguration($conf, $typoscript[$type . '.']);
        }
    }
}
