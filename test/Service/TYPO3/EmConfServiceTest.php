<?php
namespace AOE\TYPO3CLITools\Tests\Service\TYPO3;

use AOE\TYPO3CLITools\Service\TYPO3\EmConfService;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use PHPUnit\Framework\TestCase;

/**
 * @package AOE\TYPO3CLITools\Service\TYPO3
 */
class EmConfServiceTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $root;

    /**
     * @var vfsStreamFile
     */
    private $emConf;

    /**
     * initialize
     */
    public function setUp()
    {
        $this->root = vfsStream::setup();
        $this->emConf = vfsStream::newFile('ext_emconf.php')
            ->setContent(file_get_contents(dirname(__FILE__) . '/fixtures/ext_emconf.php'))
            ->at($this->root);
    }

    /**
     * @test
     */
    public function shouldUpdateVersionInEmConf()
    {
        $service = new EmConfService($this->root->url(), 'extension_manager');
        $service->updateVersion('47.11.4711');
        $service->write();

        $this->assertRegExp('~\'version\' => \'47\.11\.4711\'~', $this->emConf->getContent());
    }
}
