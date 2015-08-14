<?php
namespace AOE\TYPO3CLITools\Command;

use AOE\TYPO3CLITools\Service\TYPO3\EmConfService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package AOE\TYPO3CLITools\Command
 */
class EmConfCommand extends Command
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('emconf')
            ->setDescription('Update TYPO3´s EM_CONF')
            ->addArgument(
                'path',
                InputArgument::REQUIRED,
                'Path to the TYPO3 extension'
            )
            ->addArgument(
                'extension',
                InputArgument::REQUIRED,
                'TYPO3 extension key'
            )
            ->addOption(
                'version-number',
                'vn',
                InputOption::VALUE_REQUIRED,
                'Version number to update'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $service = new EmConfService($input->getArgument('path'), $input->getArgument('extension'));

        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $output->writeln(
                sprintf(
                    '<info>Updating EMCONF for extension "%s" in path "%s".</info>',
                    $input->getArgument('extension'),
                    $input->getArgument('path')
                )
            );
        }

        if ($input->getOption('version-number')) {
            if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                $output->writeln(
                    sprintf(
                        '<info>...updating version to "%s".</info>',
                        $input->getOption('version-number')
                    )
                );
            }
            $service->updateVersion($input->getOption('version-number'));
        }

        $service->write();
    }
}
