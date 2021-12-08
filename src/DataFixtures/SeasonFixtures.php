<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    const SEASONS = [
        [
            'program' => 'program_1',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium, dolor, laboriosam quis, officiis doloribus beatae illo quos porro magni deleniti ipsum molestiae accusamus rem quibusdam quam? Quidem voluptates fugit eveniet?',
            'year' => '2008',
            'number' => '1',
        ],
        [
            'program' => 'program_1',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium, dolor, laboriosam quis, officiis doloribus beatae illo quos porro magni deleniti ipsum molestiae accusamus rem quibusdam quam? Quidem voluptates fugit eveniet?',
            'year' => '2008',
            'number' => '2',
        ],
        [
            'program' => 'program_2',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium, dolor, laboriosam quis, officiis doloribus beatae illo quos porro magni deleniti ipsum molestiae accusamus rem quibusdam quam? Quidem voluptates fugit eveniet?',
            'year' => '2008',
            'number' => '3',
        ],
        [
            'program' => 'program_3',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium, dolor, laboriosam quis, officiis doloribus beatae illo quos porro magni deleniti ipsum molestiae accusamus rem quibusdam quam? Quidem voluptates fugit eveniet?',
            'year' => '2008',
            'number' => '4',
        ],
        [
            'program' => 'program_4',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium, dolor, laboriosam quis, officiis doloribus beatae illo quos porro magni deleniti ipsum molestiae accusamus rem quibusdam quam? Quidem voluptates fugit eveniet?',
            'year' => '2008',
            'number' => '5',
        ],
    ];
    public function load(ObjectManager $manager): void
    {
        foreach (self::SEASONS as $key => $show) {
            $season = new Season();

            $season->setprogram($this->getReference($show['program']));
            $season->setDescription($show['description']);
            $season->setYear($show['year']);
            $season->setNumber($show['number']);
            $manager->persist($season);
            $this->addReference('season_' . $key, $season);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            ProgramFixtures::class
        ];
    }
}