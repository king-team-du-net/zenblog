<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\DataFixtures\FakerTrait;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

final class ContactFixtures extends Fixture
{
    use FakerTrait;

    /**
     * @param EntityManagerInterface $manager
     */
    public function load(ObjectManager $manager): void
    {
        for ($index = 1; $index <= 5; ++$index) {
            $c = (new Contact());
            $c
                ->setIp($this->faker()->ipv4)
                ->setFullname($this->faker()->lastName() . ' ' . $this->faker()->firstName())
                ->setEmail($this->faker()->email())
                ->setSubject($this->faker()->words(2, true))
                ->setMessage($this->faker()->realText(500))
                ->setIsSend(false)
            ;

            $manager->persist($c);
        }

        $manager->flush();
    }
}
