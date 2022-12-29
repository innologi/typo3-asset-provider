<?php
namespace Innologi\TYPO3AssetProvider\Provider;

/**
 * Javascript Asset Provider
 *
 * Utilizes TYPO3 PageRenderer, ContentObject, TSFE and PageGenerator api
 *
 * @package TYPO3AssetProvider
 * @author Frenck Lutke
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 2 or later
 */
class JavascriptProvider extends ProviderAbstract
{

    /**
     * Default asset configuration as utilized by PageRenderer
     *
     * Notes:
     * - 'compress' is disabled by default for Libs via PageRenderer, possibly due
     * to a bug with external files, but we enable it by default
     *
     * @var array
     */
    protected $defaultConfiguration = [
        'placeInFooter' => false,
        'type' => 'text/javascript',
        'forceOnTop' => false,
        'async' => false,
    ];

    /**
     * Add Library Asset
     *
     * @param array $conf
     * @param string $id
     * @return void
     */
    public function addLibrary(array $conf, string $id = ''): void
    {
        $this->addFile($conf, $id);
    }

    /**
     * Add File Asset
     *
     * @param array $conf
     * @param string $id
     * @return void
     */
    public function addFile(array $conf, string $id = ''): void
    {
        $file = $this->getStreamlinedFileName($conf['file']);
        $type = $conf['type'] !== false ? ' type="' . htmlspecialchars($conf['type']) . '"' : '';
        $async = $conf['async'] !== false ? ' async="async"' : '';
        $tag = '<script src="' . htmlspecialchars($file) . '"' . $type . $async . '></script>';

        $inFooter = (bool)$conf['placeInFooter'];

        if ((bool)$conf['forceOnTop']) {
            if ($inFooter) {
                \array_unshift($this->footerFiles, $tag);
            } else {
                \array_unshift($this->headerFiles, $tag);
            }
        } else {
            if ($inFooter) {
                $this->footerFiles[] = $tag;
            } else {
                $this->headerFiles[] = $tag;
            }
        }
    }

    /**
     * Add Inline Asset
     *
     * @param string $inline
     * @param array $conf
     * @param string $id
     * @return void
     */
    public function addInline(string $inline, array $conf, string $id = ''): void
    {
        $conf['file'] = \TYPO3\CMS\Core\Utility\GeneralUtility::writeJavaScriptContentToTemporaryFile($inline);
        $this->addFile($conf, $id);
    }
}