<?php
namespace Mouf\Utils\Patcher\Commands;

use Mouf\Utils\Patcher\Dumper\Dumper;
use Mouf\Utils\Patcher\PatchInterface;
use Mouf\Utils\Patcher\PatchService;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Command to revert a given patch
 */
class RevertPatchCommand extends Command
{
    /**
     * @var PatchService
     */
    private $patchService;

    public function __construct(PatchService $patchService)
    {
        parent::__construct();
        $this->patchService = $patchService;
    }


    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
        ->setName('patches:revert')
        ->setDescription('Revert a patch.')
        ->addArgument(
            'name',
            InputArgument::REQUIRED,
            'The name of the patch instance to be reverted'
        )
        ->setHelp(<<<EOT
Reverts a patch. You must pass in parameter the name of the patch.
EOT
        );

        $this->addOption('dump', 'd', InputOption::VALUE_NONE, 'Dumps the patch to the output. Note: this is not a "dry" mode. The patch will still be reverted.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('dump')) {
            $this->patchService->setDumper(new Dumper($output));
        }

        $patchName = $input->getArgument('name');
        $this->patchService->revert($patchName);

        $output->writeln('Patch successfully reverted');
    }
}
