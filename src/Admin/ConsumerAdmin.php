<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Route\RouteCollection;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Symfony\Component\Form\Extension\Core\Type\{TextType, ChoiceType};
use App\DBAL\Types\{ConsumerType, SexType};


final class ConsumerAdmin extends AbstractAdmin
{
    protected $classnameLabel = '用户信息';

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('nick_name', null, [
                'label' => '昵称'
            ])
            ->add('sex', null, [
                'label' => '性别'
            ], ChoiceType::class, [
                'choices' => SexType::getChoices()
            ])
            // ->add('deleted_at')
            ->add('last_login_at', 'doctrine_orm_date', [
                'label' => '最后一次登录时间'
            ])
            ->add('first_login_at', 'doctrine_orm_date', [
                'label' => '第一次登录时间'
            ])
            ->add('member.market.name', 'doctrine_orm_model', [
                'label' => '用户类型'
            ], null, [
                'multiple' => true,
                'class' => 'App\Entity\Marketing'
            ])
            ->add('member.recharge_at', 'doctrine_orm_date', [
                'label' => '充值时间'
            ])
            ->add('member.expire_at', 'doctrine_orm_date', [
                'label' => '到期时间'
            ])
            ->add('created_at', 'doctrine_orm_date')
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
            ->add('member.market', null, [
                'label' => '会员类型'
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
                    'receipt_infos' => [
                        'template' => 'admin/consumer_receipt_infos.html.twig'
                    ]
                ],
            ]);
    }

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
            ->add('member.market', null, [
                'label' => '会员类型'
            ])
            // ->add('type', null, [
            //     'label' => '类型'
            // ])
            ->add('sex', null, [
                'label' => '性别'
            ])
            ->add('deleted_at', 'datetime', [
                'label' => '删除时间',
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('last_login_at', 'datetime', [
                'label' => '最后一次登录时间',
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('first_login_at', 'datetime', [
                'label' => '第一次登录时间',
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('created_at', 'datetime', [
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
            ;
    }

    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, ['edit', 'show'])) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get('id');

        $menu->addChild('consumer_show', [
            'uri' => $admin->generateUrl('show', ['id' => $id])
        ]);
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
