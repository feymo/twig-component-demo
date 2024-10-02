<?php

namespace App\Twig\Components;

use App\Repository\PersonRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/person_card_list.html.twig')]
final class PersonCardList
{
    public function __construct(private readonly PersonRepository $personRepository)
    {
    }

    public function getBookmarkedPersons(): array
    {
        return $this->personRepository->findBy(['isBookmarked' => true]);
    }
}
