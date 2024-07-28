<?php

namespace App\Entity;

use App\Repository\RedirectRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity(repositoryClass: RedirectRepository::class),
    ORM\Table(name: 'redirects'),
    ORM\HasLifecycleCallbacks
]
class Redirect
{
    #[ORM\Column(name: 'created_at')]
    private DateTimeImmutable $createdAt;

    private function __construct(
        #[ORM\Column, ORM\Id]
        public readonly string $id,
        #[ORM\Column(name: 'movie_id')]
        public readonly string $movieId,
    ) {
    }

    #[ORM\PrePersist]
    public function onBeforePersist(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public static function fromMovieId(string $movieId): self
    {
        return new self(
            sha1((new DateTimeImmutable())->getTimestamp() . $movieId),
            $movieId
        );
    }
}