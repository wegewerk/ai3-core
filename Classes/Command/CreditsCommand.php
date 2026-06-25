<?php

namespace Wegewerk\Ai3Core\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wegewerk\Ai3Core\Api\ZakAiAccount;

#[AsCommand(
    name: 'ai3_core:credits',
    description: 'Query Credits Endpoint',
)]
class CreditsCommand extends Command
{
    public function __construct(private readonly ZakAiAccount $client)
    {
        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $credits = $this->client->queryCredits();
        dump($credits);
        return 0;
    }
}
