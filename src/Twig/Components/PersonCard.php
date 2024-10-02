<?php

namespace App\Twig\Components;

use App\Entity\Person;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/person_card.html.twig')]
final class PersonCard
{
    public Person $person;

    public function getPersonFullName(): string
    {
        return sprintf('%s %s', $this->person->firstName, $this->person->lastName);
    }
}
