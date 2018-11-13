<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\{TextType, ChoiceType};
use App\DBAL\Types\{ConsumerType, SexType};


final class ConsumerAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('nick_name', null, [
                'label' => '昵称'
            ])
            ->add('type', null, [
                'label' => '用户类型',
            ], ChoiceType::class, [
                'choices' => ConsumerType::getChoices()
            ])
            ->add('sex', null, [
                'label' => '性别'
            ])
            // ->add('deleted_at')
            ->add('last_login_at', null, [
                'lable' => '最后一次登录时间'
            ])
            ->add('first_login_at', null, [
                'lable' => '第一次登录时间'
            ])
            ->add('member.recharge_at', null, [
                'label' => '充值时间'
            ])
            ->add('member.expire_at', null, [
                'label' => '到期时间'
            ])
            ->add('created_at', null, [
                'lable' => '创建时间'
            ])
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('image', 'image', [
                'label' => '图片',
                'prefix' => '/',
                'width' => 45,
                'height' => 45,
            ])
            ->add('nick_name', null, [
                'label' => '昵称'
            ])
            ->add('type', 'choice', [
                'label' => '类型',
                'choices' => ConsumerType::getReadableValues()
            ])
            ->add('sex', 'choice', [
                'label' => '性别',
                'choices' => SexType::getReadableValues()
            ])
            // ->add('deleted_at', 'datetime', [
            //     'label' => '删除时间',
            //     'format' => 'Y-m-d H:i:s'
            // ])
            ->add('last_login_at', 'datetime', [
                'label' => '最后一次登录时间',
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('first_login_at', 'datetime', [
                'label' => '第一次登录时间',
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('created_at', 'datetime', [
                'label' => '创建时间',
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('member.recharge_at', 'datetime', [
                'label' => '充值时间',
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('member.expire_at', 'datetime', [
                'label' => '到期时间',
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    // 'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    // protected function configureFormFields(FormMapper $formMapper)
    // {
    //     $formMapper
    //         ->add('id')
    //         ->add('image')
    //         ->add('nick_name')
    //         ->add('type')
    //         ->add('sex')
    //         ->add('deleted_at')
    //         ->add('last_login_at')
    //         ->add('first_login_at')
    //         ->add('created_at')
    //         ->add('updated_at')
    //         ;
    // }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('image', 'image', [
                'label' => '图片',
                'prefix' => '/',
                'width' => 45,
                'height' => 45,
            ])
            ->add('nick_name', null, [
                'label' => '昵称'
            ])
            ->add('type', null, [
                'label' => '类型'
            ])
            ->add('sex', null, [
                'label' => '性别'
            ])
            ->add('deleted_at')
            ->add('last_login_at')
            ->add('first_login_at')
            ->add('created_at')
            ->add('updated_at')
            ;
    }
}
