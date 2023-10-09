<?php
namespace Innologi\TYPO3AssetProvider\Provider;

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
        'forceOnTop' => false,
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
        $title = isset($conf['title'][0]) ? ' title="' . htmlspecialchars((string) $conf['title']) . '"' : '';
        $tag = '<link rel="' . htmlspecialchars((string) $conf['rel'])
            . '" href="' . htmlspecialchars((string) $file)
            . '" media="' . htmlspecialchars((string) $conf['media']) . '"'
            . $title
            . ($this->pageRenderer->getRenderXhtml() ? ' /' : '') . '>';

        if ((bool)($conf['forceOnTop'] ?? false)) {
            \array_unshift($this->headerFiles, $tag);
        } else {
            $this->headerFiles[] = $tag;
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
        $conf['file'] = \TYPO3\CMS\Core\Utility\GeneralUtility::writeStyleSheetContentToTemporaryFile($inline);
        $this->addFile($conf, $id);
    }
}
