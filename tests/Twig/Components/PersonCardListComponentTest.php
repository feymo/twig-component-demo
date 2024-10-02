<?php

declare(strict_types=1);

namespace App\Tests\Twig\Components;

use App\Twig\Components\PersonCardList;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\UX\TwigComponent\Test\InteractsWithTwigComponents;

class PersonCardListComponentTest extends KernelTestCase
{
    use InteractsWithTwigComponents;

    public function testComponentMount(): void
    {
        $component = $this->mountTwigComponent(
            name: PersonCardList::class,
        );

        self::assertInstanceOf(PersonCardList::class, $component);
        self::assertCount(3, $component->getBookmarkedPersons());
    }

    public function testComponentRenders(): void
    {
        $rendered = $this->renderTwigComponent(
            name: PersonCardList::class,
        );

        self::assertStringContainsString('Contacts Favoris', $rendered->toString());
        self::assertCount(1, $rendered->crawler()->filter('section'));
    }
}
