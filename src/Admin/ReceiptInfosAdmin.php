<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;


final class ReceiptInfosAdmin extends AbstractAdmin
{
    protected $classnameLabel = '收货人信息';

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id', null, [
                'label' => '编号'
            ])
            ->add('name', null, [
                'label' => '收货人姓名'
            ])
            ->add('created_at', 'doctrine_orm_date')
            ->add('updated_at', 'doctrine_orm_date')
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, [
                'label' => '编号'
            ])
            ->add('consumer.nick_name', null, [
                'label' => '用户昵称',
            ])
            ->add('name', null, [
                'label' => '收货人姓名'
            ])
            ->add('contact', null, [
                'label' => '联系电话'
            ])
            ->add('province', null, [
                'label' => '省'
            ])
            ->add('city', null, [
                'label' => '市'
            ])
            ->add('district', null, [
                'label' => '区'
            ])
            ->add('detailed_address', null, [
                'label' => '详细地址'
            ])
            ->add('created_at', null, [
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('updated_at', null, [
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name', null, [
                'label' => '收货人姓名'
            ])
            ->add('contact', null, [
                'label' => '联系电话'
            ])
            ->add('province', null, [
                'label' => '省'
            ])
            ->add('city', null, [
                'label' => '市'
            ])
            ->add('district', null, [
                'label' => '区'
            ])
            ->add('detailed_address', null, [
                'label' => '详细地址'
            ])
            ->add('created_at', null, [
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('updated_at', null, [
                'format' => 'Y-m-d H:i:s'
            ])
            ;
    }
}