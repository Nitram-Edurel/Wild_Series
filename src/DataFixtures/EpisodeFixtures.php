<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public const EPISODES = [
        [
            'title' => 'TOMBE 2 o',
            'synopsis' => 'Quand un avion tombe, y\'a des morts',
            'season' => 'season_1',
            'number' => 2,
        ],
        [
            'title' => 'TOMBE 2 o 2',
            'synopsis' => 'Quand un avion tombe, y\'a des morts',
            'season' => 'season_2',
            'number' => 2,
        ],
        [
            'title' => 'TOMBE 2 o 3',
            'synopsis' => 'Quand un avion tombe, y\'a des morts',
            'season' => 'season_3',
            'number' => 2,
        ],
        [
            'title' => 'TOMBE 2 o 4',
            'synopsis' => 'Quand un avion tombe, y\'a des morts',
            'season' => 'season_4',
            'number' => 2,
        ],
        [
            'title' => 'TOMBE 2 o 5',
            'synopsis' => 'Quand un avion tombe, y\'a des morts',
            'season' => 'season_5',
            'number' => 2
        ]
    ];
    public function load(ObjectManager $manager): void
    {
        foreach (self::EPISODES as $key => $show) {
            $episode = new Episode();

            $episode->setTitle($show['title']);
            $episode->setSynopsis($show['synopsis']);
            $episode->setNumber($show['number']);
            $episode->setSeason($this->getReference($show['season']));
            $manager->persist($episode);
        }

        $manager->flush();
    }

    public function getDependencies()

    {
        return [
            SeasonFixtures::class
        ];
    }
}
