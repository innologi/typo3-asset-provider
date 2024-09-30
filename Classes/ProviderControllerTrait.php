<?php
namespace Innologi\TYPO3AssetProvider;

use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3Fluid\Fluid\View\ViewInterface;

/**
 * Provider Controller Trait
 *
 * @package TYPO3AssetProvider
 * @author Frenck Lutke
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 2 or later
 */
trait ProviderControllerTrait
{
    protected RequestInterface $request;

    /**
     *
     * @var \Innologi\TYPO3AssetProvider\ProviderServiceInterface
     */
    protected $assetProviderService;

    /**
     *
     * @param \Innologi\TYPO3AssetProvider\ProviderServiceInterface $assetProviderService
     * @return void
     */
    public function injectAssetProviderService(\Innologi\TYPO3AssetProvider\ProviderServiceInterface $assetProviderService): void
    {
        $this->assetProviderService = $assetProviderService;
    }

    protected function initializeView(ViewInterface $view): void
    {
        if ($this->request->getFormat() === 'html') {
            // provide assets as configured per action
            $this->assetProviderService->provideAssets(
                $this->request->getControllerExtensionKey(),
                $this->request->getControllerName(),
                $this->request->getControllerActionName()
            );
        }
    }
}
