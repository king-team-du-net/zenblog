<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\DataFixtures\FakerTrait;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

final class ContactFixtures extends Fixture
{
    use FakerTrait;

    public function load(ObjectManager $manager): void
    {
        for ($index = 1; $index <= 5; ++$index) {
            $c = new Contact();
            $c->setIp($this->faker()->ipv4);
            $c->setFullname($this->faker()->lastName() . ' ' . $this->faker()->firstName());
            $c->setEmail($this->faker()->email());
            $c->setSubject($this->faker()->words(2, true));
            $c->setMessage($this->faker()->realText(500));
            $c->setIsSend(false);

            $manager->persist($c);
        }

        $manager->flush();
    }
}
