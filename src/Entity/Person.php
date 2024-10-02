<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    public ?string $lastName = null;

    #[ORM\Column(length: 50)]
    public ?string $firstName = null;

    #[ORM\Column(length: 255)]
    public ?string $avatar = null;

    #[ORM\Column(type: Types::TEXT)]
    public string $bio = '';

    #[ORM\Column(length: 255)]
    public string $profileLink = '#';

    #[ORM\Column]
    public bool $isBookmarked = false;

    public function __construct(
        string $lastName,
        string $firstName,
        string $avatar,
        string $bio = '',
        string $profileLink = '#'
    ) {
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->avatar = $avatar;
        $this->bio = $bio;
        $this->profileLink = $profileLink;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
