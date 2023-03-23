<?php
declare(strict_types=1);

namespace App\Validator;

use App\Entity\Price as PriceEntity;
use App\Repository\PriceRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PriceValidator extends ConstraintValidator
{
    /**
     * @param PriceRepository $priceRepository Repo
     */
    public function __construct(private PriceRepository $priceRepository)
    {
    }

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        /** @var App\Validator\Price $constraint */

        if (
            !$value instanceof PriceEntity ||
            !$value->getVariant() ||
            !$value->getCurrency() ||
            !$value->getPrice()
        ) {
            return;
        }

        $qb = $this->priceRepository->createQueryBuilder('p');
        $qb->select('count(p.id)')
            ->where('p.currency = :currency')
            ->andWhere('p.variant = :variant');

        if ($value->getId()) {
            $qb->andWhere('p.id != :id')->setParameter('id', $value->getId());
        }

        $cnt = $qb
            ->setParameter('currency', $value->getCurrency())
            ->setParameter('variant', $value->getVariant())
            ->getQuery()->getSingleScalarResult();

        if ($cnt > 0) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', 'self')
                ->atPath('price')
                ->addViolation();
        }
    }
}
