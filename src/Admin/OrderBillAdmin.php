<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\{TextType, ChoiceType};
use App\DBAL\Types\OrderBillType;


final class OrderBillAdmin extends AbstractAdmin
{
    protected $classnameLabel = '订单细单';

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        if(!$this->isChild())
        {
            $datagridMapper->add('order_info', null, [
                'label' => '订单',
            ], null, [
                'multiple' => true
            ]);
        }

        $datagridMapper
            ->add('deposit_price', 'doctrine_orm_number', [
                'label' => '押金',
                'currency' => '￥'
            ])
            ->add('status', null, [
                'label' => '状态'
            ], ChoiceType::class, [
                'choices' => OrderBillType::getChoices()
            ])
            ->add('created_at', 'doctrine_orm_date')
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id');

        if(!$this->isChild())
        {
            $listMapper->add('order_info', null, [
                'label' => '订单',
                'route' => ['name' => 'show']
            ]);
        }

        $listMapper
            ->add('deposit_price', 'currency', [
                'label' => '押金',
                'currency' => '￥'
            ])
            ->add('status', 'choice', [
                'label' => '状态',
                'choices' => OrderBillType::getReadableValues()
            ])
            // ->add('quantity')
            ->add('created_at', null, [
                'format' => 'Y-m-d H:i:s'
            ]);
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('deposit_price')
            ->add('status')
            ->add('created_at')
            ;
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();
        unset($actions['delete']);

        return $actions;
    }

    public function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ->remove('delete');
    }
}
