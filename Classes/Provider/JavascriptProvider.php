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
        'disableCompression' => false,
        'forceOnTop' => false,
        'excludeFromConcatenation' => false,
        'allWrap' => '',
        'splitChar' => '|',
        'async' => false,
        'integrity' => ''
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
        $methodName = (bool) $conf['placeInFooter'] ? 'addJsFooterLibrary' : 'addJsLibrary';
        // @see http://docs.typo3.org/typo3cms/TyposcriptReference/Setup/Page/Index.html#includejslibs-array
        $this->pageRenderer->$methodName(
            $id,
            $conf['file'],
            $conf['type'],
            ! ((bool) $conf['disableCompression']),
            (bool) $conf['forceOnTop'],
            $conf['allWrap'],
            (bool) $conf['excludeFromConcatenation'],
            $conf['splitChar'],
            $conf['async'],
            $conf['integrity']
        );
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
        // @TODO test this
        $methodName = (bool) $conf['placeInFooter'] ? 'addJsFooterFile' : 'addJsFile';
        // @see http://docs.typo3.org/typo3cms/TyposcriptReference/Setup/Page/Index.html#includejs-array
        $this->pageRenderer->$methodName(
            $conf['file'],
            $conf['type'],
            ! ((bool) $conf['disableCompression']),
            (bool) $conf['forceOnTop'],
            $conf['allWrap'],
            (bool) $conf['excludeFromConcatenation'],
            $conf['splitChar'],
            $conf['async'],
            $conf['integrity']
        );
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
        // PageRenderer does not check if removeDefaultJS is set, so we need to instead
        // @see \TYPO3\CMS\Frontend\Page\PageGenerator::renderContentWithHeader() (~line:865)
        if (isset($GLOBALS['TSFE']->config['config']['removeDefaultJS']) && $GLOBALS['TSFE']->config['config']['removeDefaultJS'] === 'external') {
            if (version_compare(TYPO3_version, '9.4', '<')) {
                // @extensionScannerIgnoreLine
                $conf['file'] = \TYPO3\CMS\Frontend\Page\PageGenerator::inline2TempFile($inline, 'js');
            } else {
                $conf['file'] = \TYPO3\CMS\Core\Utility\GeneralUtility::writeJavaScriptContentToTemporaryFile($inline);
            }
            $this->addFile($conf, $id);
        } else {
            $methodName = (bool) $conf['placeInFooter'] ? 'addJsFooterInlineCode' : 'addJsInlineCode';
            // @see http://docs.typo3.org/typo3cms/TyposcriptReference/Setup/Page/Index.html#jsinline
            $this->pageRenderer->$methodName(
                $id,
                $inline,
                ! ((bool) $conf['disableCompression']),
                (bool) $conf['forceOnTop']
            );
        }
    }
}