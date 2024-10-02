<?php

namespace App\Twig\Components;

use App\Entity\Person;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsTwigComponent(template: 'components/person_card.html.twig')]
final class PersonCard
{
    public ?Person $person = null;

    #[PostMount]
    public function setDefaultValues(): void
    {
        if ($this->person === null) {
            $this->person = new Person(
                'Doe',
                'John',
                'https://static-00.iconduck.com/assets.00/avatar-default-icon-2048x2048-h6w375ur.png',
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                '#'
            );
        }
    }

    public function getPersonFullName(): string
    {
        return sprintf('%s %s', $this->person->firstName, $this->person->lastName);
    }
}
