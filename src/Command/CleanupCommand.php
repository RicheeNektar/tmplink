<?php

namespace App\Command;

use App\Repository\RedirectRepository;
use DateInterval;
use DateTimeImmutable;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:cleanup')]
class CleanupCommand extends Command
{
    public function __construct(
        private readonly RedirectRepository $redirectRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly int $linkLifetime,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $batchSize = 100;

        $total = 0;
        $date = (new DateTimeImmutable())->sub(new DateInterval(sprintf('PT%sS', $this->linkLifetime)));

        do {
            $batch = $this->redirectRepository->matching(
                Criteria::create()
                    ->where(
                        Criteria::expr()->lte('createdAt', $date)
                    )
                    ->setMaxResults($batchSize),
            );

            foreach ($batch as $item) {
                $this->entityManager->remove($item);
            }

        } while (count($batch) === $batchSize);

        $this->entityManager->flush();

        $output->writeln(sprintf('Cleaned up %s codes', $total));
        return Command::SUCCESS;
    }
}