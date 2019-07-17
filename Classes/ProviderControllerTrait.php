<?php
namespace Innologi\TYPO3AssetProvider;

use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Fluid\View\AbstractTemplateView;

/**
 * Provider Controller Trait
 *
 * @package TYPO3AssetProvider
 * @author Frenck Lutke
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 2 or later
 */
trait ProviderControllerTrait
{

    /**
     *
     * @var \TYPO3\CMS\Extbase\Mvc\Request
     */
    protected $request;

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
    public function injectAssetProviderService(\Innologi\TYPO3AssetProvider\ProviderServiceInterface $assetProviderService)
    {
        $this->assetProviderService = $assetProviderService;
    }

    /**
     *
     * @param \TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view
     * @return void
     */
    protected function initializeView(ViewInterface $view): void
    {
        if ($view instanceof AbstractTemplateView && $this->request->getFormat() === 'html') {
            // provide assets as configured per action
            $this->assetProviderService->provideAssets(
                $this->request->getControllerExtensionKey(),
                $this->request->getControllerName(),
                $this->request->getControllerActionName()
            );
        }
        parent::initializeView();
    }
}
