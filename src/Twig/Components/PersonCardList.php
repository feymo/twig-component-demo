<?php

namespace App\Twig\Components;

use App\Repository\PersonRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent(template: 'components/person_card_list.html.twig')]
final class PersonCardList
{
    use DefaultActionTrait;

    public array $bookmarkedPersons;

    public function __construct(private readonly PersonRepository $personRepository)
    {
        $this->bookmarkedPersons = $this->personRepository->findBy(['isBookmarked' => true]);
    }

    #[LiveListener('bookmarkRemoved')]
    public function updateBookmarkedPersons(): void
    {
        $this->bookmarkedPersons = $this->personRepository->findBy(['isBookmarked' => true]);
    }
}
