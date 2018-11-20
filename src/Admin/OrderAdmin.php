<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\{TextType, ChoiceType};
use App\DBAL\Types\OrderType;


final class OrderAdmin extends AbstractAdmin
{
    protected $classnameLabel = '订单';

    protected $datagridValues = array (
        'order_number' => '', // type 2 : >
        '_page' => 1, // Display the first page (default = 1)
        '_sort_order' => 'DESC', // Descendant ordering (default = 'ASC')
        '_sort_by' => 'id' // name of the ordered field (default = the model id field, if any)
        // the '_sort_by' key can be of the form 'mySubModel.mySubSubModel.myField'.
    );

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id', null, [
                'label' => '编号'
            ])
            ->add('consignee_name', null, [
                'label' => '收货人姓名'
            ])
            ->add('consignee_concat', null, [
                'label' => '收货人电话'
            ])
            // ->add('consignee_adress', null, [
            //     'label' => '收货人地址'
            // ])
            ->add('order_number', null, [
                'show_filter' => true,
                'label' => '订单编号'
            ])
            ->add('status', null, [
                'show_filter' => true,
                'label' => '订单状态'
            ], ChoiceType::class, [
                'choices' => OrderType::getChoices()
            ])
            ->add('deleted_at')
            ->add('logistics_number')
            ->add('created_at')
            ->add('updated_at')
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('consignee_name', null, [
                'label' => '收货人姓名'
            ])
            ->add('consignee_concat', null, [
                'label' => '收货人电话'
            ])
            // ->add('consignee_adress', null, [
            //     'label' => '收货人地址'
            // ])
            ->add('order_number', null, [
                'show_filter'=>true
            ])
            ->add('status', 'choice', [
                'label' => '状态',
                'choices' => OrderType::getReadableValues()
            ])
            // ->add('deleted_at')
            ->add('logistics_number', null, [
                'label' => '物流单号',
            ])
            ->add('created_at', null, [
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('updated_at', null, [
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('_action', null, [
                'actions' => [
                    'show' => []
                ],
            ]);
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('consignee_name', null, [
                'label' => '收货人姓名'
            ])
            ->add('consignee_concat', null, [
                'label' => '收货人电话'
            ])
            // ->add('consignee_adress', null, [
            //     'label' => '收货人地址'
            // ])
            ->add('order_number', null, [
                'show_filter'=>true
            ])
            ->add('status', 'choice', [
                'label' => '状态',
                'choices' => OrderType::getReadableValues()
            ])
            // ->add('deleted_at')
            ->add('logistics_number', null, [
                'label' => '物流单号',
            ])
            ->add('created_at', null, [
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('updated_at', null, [
                'format' => 'Y-m-d H:i:s'
            ])
            ;
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();
        unset($actions['delete']);

        return $actions;
    }

    public function configureRoutes(RouteCollection $collection) {
        $collection
            ->remove('create')
            ->remove('delete');
    }
}
