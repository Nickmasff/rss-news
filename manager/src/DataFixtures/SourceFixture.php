<?php

namespace App\DataFixtures;

use App\Model\News\Entity\News\Source;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SourceFixture extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $lenta = new Source('Lenta.ru : Новости', 'https://lenta.ru/rss/news');
        $manager->persist($lenta);

//        $vedomosti = new Source('"Ведомости". Ежедневная деловая газета', 'https://www.vedomosti.ru/rss/news');
//        $manager->persist($vedomosti);

        $rbk = new Source('РБК - Все материалы', 'http://static.feed.rbc.ru/rbc/logical/footer/news.rss');
        $manager->persist($rbk);

        $manager->flush();
    }


}
