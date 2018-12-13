<?php
namespace App\Controller;

use Doctrine\ORM\EntityManager;
use ApiPlatform\Core\Exception\InvalidArgumentException;
use App\Entity\Order;
use App\DBAL\Types\{OrderType, OrderBillType};


class OrderSpecial
{
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    private function build_order_no()
    {
        /* 选择一个随机的方案 */
        mt_srand((double) microtime() * 1000000);

        return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    public function __invoke(Order $data, $id): Order
    {
        $total_stat = ["inc" => 0, "excl" => 0];

        $orderBills = $data->getOrderBill();
        // $goods_ids = $this->getGoodsIds($orderBill);
        // $consumer = $this->em->find('App\Entity\Consumer', 1);

        foreach($orderBills as $orderBill)
        {
            $goods = $this->em->find('App\Entity\Goods', $orderBill->getGoodsId());
            $orderBill_status = $orderBill->getStatus();
            $price = $goods->getDepositPrice();

            if($orderBill_status == OrderBillType::APPEND)
            {
                $total_stat['inc'] += $price;
            }
            else if($orderBill_status == OrderBillType::RETURN)
            {
                $total_stat['excl'] += $price;
            }
            else
            {
                throw new InvalidArgumentException('orderBill status not in enum');
            }

            $orderBill
                ->setQuantity(1)
                ->setDepositPrice($price)
                ->setGoods($goods);
        }

        $total_excl = $total_stat['inc'] - $total_stat['excl'];

        $order = $data
               ->setOrderNumber('YH' . $this->build_order_no())
               ->setStatus(OrderType::WAIT_PAY)
               ->setTotal($total_stat['inc'])
               ->setTotalExcl($total_excl)
               ->setConsumer($this->em->getReference('App\Entity\Consumer', $id));

        return $order;
    }
}
