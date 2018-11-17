<?php
namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Entity\ReceiptInfo;


class ReceiptInfoSpecial
{
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function __invoke(ReceiptInfo $data, $id): ReceiptInfo
    {
        $receiptInfo = $data
                     ->setConsumer($this->em->getReference('App\Entity\Consumer', $id));

        return $receiptInfo;
    }
}
