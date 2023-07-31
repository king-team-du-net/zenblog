<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\AdCategory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

final class AdCategoryFixtures extends Fixture
{
    use FakerTrait;

    private $counter = 1;

    public function __construct(
        private readonly SluggerInterface $slugger
    ) {
    }

    /**
     * @param EntityManagerInterface $manager
     */
    public function load(ObjectManager $manager): void
    {
        $parent = $this->getAdCategoryData('Informatique', null, false, true, 1, null, null, $manager);

        $this->getAdCategoryData('Ordinateurs portables', $parent, false, true, 1, 'success', null, $manager);
        $this->getAdCategoryData('Ecrans', $parent, false, true, 1, 'danger', null, $manager);
        $this->getAdCategoryData('Souris', $parent, true, false, 1, 'primary', null, $manager);

        $parent = $this->getAdCategoryData('Mode', null, false, true, 2, null, null, $manager);

        $this->getAdCategoryData('Homme', $parent, false, true, 2, 'info', null, $manager);
        $this->getAdCategoryData('Femme', $parent, false, true, 2, 'secondary', null, $manager);
        $this->getAdCategoryData('Enfant', $parent, true, false, 2, 'warning', null, $manager);

        $manager->flush();
    }

    private function getAdCategoryData(
        string $name,
        AdCategory $parent = null,
        bool $isHidden,
        bool $isFeatured,
        int $featuredorder,
        string $color = null,
        string $icon = null,
        ObjectManager $manager
    ): AdCategory {
        $adCategory = (new AdCategory());
        $adCategory
            ->setName($name)
            ->setSlug($this->slugger->slug($adCategory->getName())->lower())
            ->setParent($parent)
            ->setIsHidden($isHidden)
            ->setIsFeatured($isFeatured)
            ->setFeaturedorder($featuredorder)
            ->setColor($color)
            ->setIcon($icon)
        ;
        $manager->persist($adCategory);

        $this->addReference('cat-'.$this->counter, $adCategory);
        $this->counter++;

        return $adCategory;
    }
}
