<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Service\Slugify;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    private $slugify;

    public const PROGRAMS = [
        [
            'title' => 'Fargo',
            'summary' => 'En 2006, à Bemidji dans le Minnesota, Lester Nygaard, interprété par Martin Freeman, est un homme effacé et sans envergure travaillant pour une compagnie d\'assurance. Sa vie bascule le jour où il décide de se confier à un homme, Lorne Malvo, interprété par Billy Bob Thornton, qui se révèle être un tueur à gages. Celui-ci tue un certain Sam Hess, qui, dans sa jeunesse, harcelait Lester au lycée. Un mensonge en entraînant un autre, Lester va radicalement changer de vie et extérioriser sa véritable personnalité, celle d\'un manipulateur prêt à tout pour être accepté par une société qui l\'a trop longtemps ignoré.',
            'category' => 'category_1',
            'actors' => ['actor_1', 'actor_4'],
            'poster' => 'http://www.slate.fr/sites/default/files/photos/fargo-poster.jpg',
        ],
        [
            'title' => 'Mr Robot',
            'summary' => 'Elliot Alderson est un jeune informaticien vivant à New York, qui travaille en tant qu\'ingénieur en sécurité informatique pour Allsafe Security. Il lutte constamment contre la dépression et son processus de pensée semble fortement influencé par la paranoïa. Il pirate les comptes des gens, ce qui le conduit souvent à agir comme un cyber-justicier.  Elliot rencontre « Mr. Robot », un mystérieux anarchiste qui souhaite le recruter dans son groupe de hackers connu sous le nom de « Fsociety ». Leur objectif consiste à rétablir l\'équilibre de la société par la destruction des infrastructures des plus grosses banques et entreprises du monde, notamment le conglomérat E Corp. (surnommé « Evil Corp. » par Elliot) qui, comme client, par ailleurs, représente 80 % du chiffre d\'affaires d’Allsafe Security.',
            'category' => 'category_10',
            'actors' => 'actor_5',
            'poster' => 'https://i.blogs.es/6dfa2f/mrrobot/450_1000.jpg',
        ],
        [
            'title' => 'The office',
            'summary' => 'Des employés de bureau au quotidien pas si monotone. Pour cause, leurs patron est Michael Scott',
            'category' => 'category_7',
            'actors' => ['actor_6', 'actor_7', 'actor_8'],
            'poster' => 'http://img.over-blog.com/358x500/0/28/78/07/Images-janv.2011/image-janv.2011.suite/the-office-season-2.jpg',
        ],
        [
            'title' => 'Domino',
            'summary' => 'Des employés de bureau au quotidien pas si monotone. Pour cause, leurs patron est Michael Scott',
            'category' => 'category_7',
            'actors' => ['actor_6', 'actor_7', 'actor_8'],
            'poster' => 'http://img.over-blog.com/358x500/0/28/78/07/Images-janv.2011/image-janv.2011.suite/the-office-season-2.jpg',
        ],
        [
            'title' => 'La fin de Tout le début de Rien',
            'summary' => 'Des employés de bureau au quotidien pas si monotone. Pour cause, leurs patron est Michael Scott',
            'category' => 'category_5',
            'actors' => ['actor_6', 'actor_7', 'actor_8'],
            'poster' => 'http://img.over-blog.com/358x500/0/28/78/07/Images-janv.2011/image-janv.2011.suite/the-office-season-2.jpg',
        ],


    ];

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::PROGRAMS as $key => $show) {
            $program = new Program();

            $program->setTitle($show['title']);
            $program->setSummary($show['summary']);
            $program->setPoster($show['poster']);
            $program->setCategory($this->getReference($show['category']));
            for ($i = 0; $i < count($show['actors']); $i++) {
                $program->addActor($this->getReference($show['actors'][$i]));
            }
            $slug = $this->slugify->generate($program->getTitle());
            $program->setSlug($slug);

            $manager->persist($program);
            $this->addReference('program_' . $key, $program);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ActorFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
