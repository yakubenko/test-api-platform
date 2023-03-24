<?php
declare(strict_types=1);

namespace App\Command\Seed;

use App\Entity\Category;
use App\Entity\Currency;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:seed-initial-data',
    description: 'Seeds initial data',
)]
class SeedInitialDataCommand extends Command
{
    /**
     * @param EntityManagerInterface $em EM
     */
    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $categories = [
            ['name' => 'Shoes', 'code' => 'shoes', 'has_multiple_prices' => false],
            ['name' => 'Jewelry', 'code' => 'jewelry', 'has_multiple_prices' => true],
        ];

        $currencies = [
            ['name' => 'Euro', 'code' => 'eur', 'sign' => '€'],
            ['name' => 'US Dollar', 'code' => 'usd', 'sign' => '$'],
        ];

        foreach ($categories as $category) {
            $categoryEntity = new Category();
            $categoryEntity->setCode($category['code']);
            $categoryEntity->setName($category['name']);
            $categoryEntity->setHasMultiplePrices($category['has_multiple_prices']);

            $this->em->persist($categoryEntity);
        }

        foreach ($currencies as $currency) {
            $currencyEntity = new Currency();
            $currencyEntity->setCode($currency['code']);
            $currencyEntity->setName($currency['name']);
            $currencyEntity->setSign($currency['sign']);

            $this->em->persist($currencyEntity);
        }

        $this->em->flush();

        $existingCats = $this->em->createQueryBuilder()
            ->select('c')
            ->from(Category::class, 'c')
            ->getQuery()
            ->getResult();

        foreach ($existingCats as $cat) {
            $product = new Product();
            $product->setCategory($cat);
            $product->setTitle('Product in category ' . $cat->getId());
            $this->em->persist($product);
        }

        $this->em->flush();

        return 0;
    }
}
