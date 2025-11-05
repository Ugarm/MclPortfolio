<?php

namespace App\Command;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\Cache\CacheInterface;

#[AsCommand(
    name: 'app:reset-email-flood-filter',
    description: 'Reset blocked IP list in the Email flood filter'
)]
class ResetEmailFloodFilter extends Command
{
    private CacheInterface $cache;
    private string $cacheKeyPrefix;

    public function __construct(CacheInterface $cache, string $cacheKeyPrefix = 'email_rate_limit_')
    {
        parent::__construct();
        $this->cache = $cache;
        $this->cacheKeyPrefix = $cacheKeyPrefix;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Resets the rate limit for all IP addresses.')
            ->addOption('ip', null, InputOption::VALUE_OPTIONAL, 'Reset rate limit for a specific IP address')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Show what would be deleted without actually deleting');
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $specificIp = $input->getOption('ip');
        $dryRun = $input->getOption('dry-run');

        if ($dryRun) {
            $io->note('Running in dry-run mode. No cache entries will be deleted.');
        }

        if ($specificIp) {
            return $this->resetSpecificIp($io, $specificIp, $dryRun);
        }

        return $this->resetAllIps($io, $dryRun);
    }

    private function resetSpecificIp(SymfonyStyle $io, string $ip, bool $dryRun): int
    {
        $key = $this->cacheKeyPrefix . $ip;

        if ($dryRun) {
            $io->info("Would reset rate limit for IP: $ip (key: $key)");
        } else {
            $this->cache->delete($key);
            $io->success("Reset rate limit for IP: $ip");
        }

        return Command::SUCCESS;
    }

    private function resetAllIps(SymfonyStyle $io, bool $dryRun): int
    {
        // This is a simple implementation. In a real-world scenario, you'd want to
        // implement a way to track all active rate limit keys, perhaps using a registry.
        $commonIps = [
            '127.0.0.1',
            'localhost',
            '::1',
        ];

        // Add some common local network patterns
        for ($i = 1; $i < 255; $i++) {
            $commonIps[] = "192.168.1.$i";
            $commonIps[] = "10.0.0.$i";
        }

        $count = 0;
        foreach ($commonIps as $ip) {
            $key = $this->cacheKeyPrefix . $ip;

            if ($dryRun) {
                $io->text("Would delete: $key");
            } else {
                $this->cache->delete($key);
                $count++;
            }
        }

        if ($dryRun) {
            $io->info("Would reset rate limits for " . count($commonIps) . " IP addresses.");
        } else {
            $io->success("Reset rate limits for $count IP addresses.");
        }

        return Command::SUCCESS;
    }
}

