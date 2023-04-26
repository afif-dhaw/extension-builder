<?php

namespace Filter\Filter\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestScheduler extends Command
{
    /**
     * Configure the command by defining the name, options and arguments
     */
    protected function configure()
    {
        $this
            ->setDescription('Test Scheduler')
            ->setHelp('Description of the test scheduler')
            ->addArgument('id', InputArgument::OPTIONAL, 'id');
    }
    /**
     * Executes the command for showing sys_log entries
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->writeln('Initiated and Processing...');
        $id = $input->getArgument('id');
        if (!$id) {
            $io->error('No id provided');
        } else {
            $io->success('Successfully run id=' . $id);
        }
        return 0;
    }
}
