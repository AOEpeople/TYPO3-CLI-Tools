<?php
namespace AOE\TYPO3CLITools\Service\TYPO3;

use TYPO3\CMS\Extensionmanager\Utility\EmConfUtility;

/**
 * @package AOE\TYPO3CLITools\Service\TYPO3
 */
class EmConfService
{
    /**
     * @var EmConfUtility
     */
    private $emConfUtility;

    /**
     * @var string
     */
    private $emConf;

    /**
     * @var string
     */
    private $emConfFileName;

    /**
     * @param string $path
     * @param string $extension
     */
    public function __construct($path, $extension)
    {
        $path = $this->normalize($path);
        $this->emConfFileName = $path . 'ext_emconf.php';
        $this->emConf = array(
            'extKey' => $extension,
            'EM_CONF' => $this->getEmConfUtility()->includeEmConf(array(
                'key' => $extension,
                'siteRelPath' => $path
            ))
        );
    }

    /**
     * @return void
     * @throws \RuntimeException
     */
    public function write()
    {
        if (false === file_put_contents($this->emConfFileName,
                $this->getEmConfUtility()->constructEmConf($this->emConf))
        ) {
            throw new \RuntimeException(sprintf('could not write file "%s"', $this->emConfFileName), 1439538605);
        }
    }

    /**
     * @param string $version
     */
    public function updateVersion($version)
    {
        $this->update('version', $version);
    }

    /**
     * @param string $key
     * @param string $value
     */
    protected function update($key, $value)
    {
        $this->emConf['EM_CONF'][$key] = $value;
    }

    /**
     * @return EmConfUtility
     */
    protected function getEmConfUtility()
    {
        if (null === $this->emConfUtility) {
            define('PATH_site', '');
            define('TAB', chr(9));
            $this->emConfUtility = new EmConfUtility();
        }

        return $this->emConfUtility;
    }

    /**
     * @param string $path
     * @return string
     */
    protected function normalize($path)
    {
        return rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }
}
