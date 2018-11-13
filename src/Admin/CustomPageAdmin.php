<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


final class CustomPageAdmin extends AbstractAdmin
{
    protected $classnameLabel = '自定义页面';

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id', null, [
                'label' => '编号'
            ])
            ->add('name', null, [
                'label' => '页面名称'
            ])
            ->add('created_at', 'doctrine_orm_date', [
                'label' => '创建时间'
            ])
            ->add('updated_at', 'doctrine_orm_date', [
                'label' => '更新时间'
            ])
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, [
                'label' => '编号'
            ])
            ->add('name', null, [
                'label' => '页面名称'
            ])
            ->add('link', null, [
                'label' => '页面链接'
            ])
            ->add('created_at', null, [
                'label' => '创建时间',
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('updated_at', null, [
                'label' => '更新时间',
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        if (!$this->isCurrentRoute('create')) {
            $formMapper
                ->add('id', null, [
                    'disabled' => true
                ])
                ->add('created_at', null, [
                    'disabled' => true
                ])
                ->add('updated_at', null, [
                    'disabled' => true
                ]);
        }

        $formMapper
            ->add('name', TextType::class, [
                'label' => '页面名称'
            ])
            ->add('link', TextType::class, [
                'label' => '链接'
            ])
            ->add('content', SimpleFormatterType::class, [
                'label' => '详情',
                'format' => 'richhtml',
                'ckeditor_context' => 'rip_config'
            ])
            ;
    }
}
