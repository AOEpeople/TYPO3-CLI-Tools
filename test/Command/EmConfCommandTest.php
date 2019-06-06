<?php

namespace AOE\TYPO3CLITools\Tests\Command;

use AOE\TYPO3CLITools\Command\EmConfCommand;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @package AOE\Tagging\Tests\Vcs
 */
class EmConfCommandTest extends TestCase
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
    public function shouldExecuteWithoutOptions()
    {
        $application = new Application();
        $application->add(new EmConfCommand());

        $command = $application->find('emconf');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'path' => $this->root->url(),
            'extension' => 'extension_manager',
        ]);

        $this->assertEmpty($commandTester->getDisplay());
    }

    /**
     * @test
     */
    public function shouldExecuteWithoutOptionsVerbose()
    {
        $application = new Application();
        $application->add(new EmConfCommand());

        $command = $application->find('emconf');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'command' => $command->getName(),
                'path' => $this->root->url(),
                'extension' => 'extension_manager',
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE,
            ]
        );
        $this->assertRegExp('~Updating EMCONF for extension "extension_manager" in path "vfs://root"~',
            $commandTester->getDisplay());
    }

    /**
     * @test
     */
    public function shouldUpdateVersionNumber()
    {
        /** @var EmConfCommand $gitCommand */
        $application = new Application();
        $application->add(new EmConfCommand());

        $command = $application->find('emconf');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'path' => $this->root->url(),
            'extension' => 'extension_manager',
            '--version-number' => '0.0.1',
        ]);

        $this->assertRegExp('~\'version\' => \'0\.0\.1\'~', $this->emConf->getContent());
    }

    /**
     * @test
     */
    public function shouldUpdateVersionNumberVerbose()
    {
        /** @var EmConfCommand $gitCommand */
        $application = new Application();
        $application->add(new EmConfCommand());

        $command = $application->find('emconf');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'command' => $command->getName(),
                'path' => $this->root->url(),
                'extension' => 'extension_manager',
                '--version-number' => '0.0.2',
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE,
            ]
        );

        $this->assertRegExp('~\.\.\.updating version to "0\.0\.2"~', $commandTester->getDisplay());
    }
}
