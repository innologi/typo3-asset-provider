<?php
namespace Innologi\TYPO3AssetProvider\Provider;

use TYPO3\CMS\Frontend\Page\PageGenerator;

/**
 * CSS Asset Provider
 *
 * Utilizes TYPO3 PageRenderer, ContentObject, TSFE and PageGenerator api
 *
 * @package TYPO3AssetProvider
 * @author Frenck Lutke
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 2 or later
 */
class CssProvider extends ProviderAbstract
{

    /**
     * Default asset configuration as utilized by PageRenderer
     *
     * Notes:
     * - 'rel' isn't influenced by any 'alternate' property, but you can overrule it
     * - 'allWrap' and 'allWrap.splitChar' are currently not supported
     *
     * @var array
     */
    protected $defaultConfiguration = [
        'rel' => 'stylesheet',
        'media' => 'all',
        'title' => '',
        'disableCompression' => false,
        'forceOnTop' => false,
        'excludeFromConcatenation' => false
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
        // @TODO test this
        // @see http://docs.typo3.org/typo3cms/TyposcriptReference/Setup/Page/Index.html#includecsslibs-array
        $this->pageRenderer->addCssLibrary(
            $conf['file'],
            $conf['rel'],
            $conf['media'],
            $conf['title'],
            ! ((bool) $conf['disableCompression']),
            (bool) $conf['forceOnTop'],
            '',
            (bool) $conf['excludeFromConcatenation']
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
        // @see http://docs.typo3.org/typo3cms/TyposcriptReference/Setup/Page/Index.html#includecss-array
        $this->pageRenderer->addCssFile(
            $conf['file'],
            $conf['rel'],
            $conf['media'],
            $conf['title'],
            ! ((bool) $conf['disableCompression']),
            (bool) $conf['forceOnTop'],
            '',
            (bool) $conf['excludeFromConcatenation']
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
        // @TODO test this
        // @see \TYPO3\CMS\Frontend\Page\PageGenerator::renderContentWithHeader() (~line:570)
        if (isset($GLOBALS['TSFE']->config['config']['inlineStyle2TempFile']) && $GLOBALS['TSFE']->config['config']['inlineStyle2TempFile']) {
            $conf['file'] = PageGenerator::inline2TempFile($inline, 'css');
            $this->addFile($conf, $id);
        } else {
            // @see http://docs.typo3.org/typo3cms/TyposcriptReference/Setup/Page/Index.html#cssinline
            $this->pageRenderer->addCssInlineBlock(
                $name,
                $block,
                ! ((bool) $conf['disableCompression']),
                (bool) $conf['forceOnTop']
            );
        }
    }
}
