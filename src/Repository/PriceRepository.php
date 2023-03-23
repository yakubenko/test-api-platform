<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Price;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use DomainException;

/**
 * @extends ServiceEntityRepository<Price>
 * @method Price|null find($id, $lockMode = null, $lockVersion = null)
 * @method Price|null findOneBy(array $criteria, array $orderBy = null)
 * @method Price[]    findAll()
 * @method Price[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PriceRepository extends ServiceEntityRepository
{
    /**
     * @inheritDoc
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Price::class);
    }

    /**
     * @param Price $price Price
     * @return void
     */
    public function handlePriceChange(Price $price)
    {
        $category = $price->getVariant()?->getProduct()?->getCategory();

        if (!$category || $category->isHasMultiplePrices()) {
            return;
        }

        $currency = $price->getCurrency();
        $variants = $price->getVariant()?->getProduct()?->getVariants();

        if (!$currency || !$variants instanceof Collection) {
            throw new DomainException('Prices can not be updated');
        }

        $qb = $this->createQueryBuilder('p');
        $qb->update(Price::class, 'p')
            ->set('p.price', ':price')
            ->add('where', $qb->expr()->in('p.variant', ':variants'))
            ->andWhere('p.currency = :currency')
            ->setParameter('price', $price->getPrice())
            ->setParameter('variants', $variants)
            ->setParameter('currency', $currency)
            ->getQuery()
            ->execute();
    }
}
