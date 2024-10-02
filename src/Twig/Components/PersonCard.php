<?php

namespace App\Twig\Components;

use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsLiveComponent(template: 'components/person_card.html.twig')]
final class PersonCard
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp(writable: ['isBookmarked'])]
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

    #[LiveAction]
    public function removeBookmark(EntityManagerInterface $entityManager): void
    {
        $this->person->isBookmarked = false;
        $entityManager->persist($this->person);
        $entityManager->flush();

        $this->emit('bookmarkRemoved', componentName: 'PersonCardList');
    }
}
