<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Admin\AdminInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Symfony\Component\Form\Extension\Core\Type\{TextType, ChoiceType};
use App\DBAL\Types\{OrderType, OrderActionType};
use App\DependencyInjection\WechatPayRefund;

final class OrderAdmin extends AbstractAdmin
{
    protected $classnameLabel = '订单';

    protected $datagridValues = array (
        '_page' => 1, // Display the first page (default = 1)
        '_sort_order' => 'DESC', // Descendant ordering (default = 'ASC')
        '_sort_by' => 'id' // name of the ordered field (default = the model id field, if any)
        // the '_sort_by' key can be of the form 'mySubModel.mySubSubModel.myField'.
    );

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        if(!$this->isChild())
        {
            $datagridMapper
                ->add('consumer', null, [
                    'label' => '用户昵称'
                ]);
        }

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
            ->add('consignee_address', null, [
                'label' => '收货人地址'
            ])
            ->add('order_number', null, [
                'show_filter' => true,
                'label' => '订单号'
            ])
            ->add('total', 'doctrine_orm_number', [
                'label' => '押金',
                'currency' => '￥'
            ])
            ->add('total_excl', 'doctrine_orm_number', [
                'label' => '押金差价',
                'currency' => '￥'
            ])
            ->add('status', 'doctrine_orm_choice', [
                'show_filter' => true,
                'label' => '订单状态'
            ], ChoiceType::class, [
                'choices' => OrderType::getChoices(),
                'multiple' => true
            ])
            // ->add('deleted_at')
            ->add('created_at', 'doctrine_orm_date')
            ->add('updated_at', 'doctrine_orm_date')
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('order_number', null, [
                'label' => '订单号',
                'show_filter'=>true
            ]);

        if(!$this->isChild())
        {
            $listMapper
                ->add('consumer', null, [
                    'label' => '用户昵称',
                    'route' => ['name' => 'show']
                ]);
        }

        $listMapper
            ->add('consignee_name', null, [
                'label' => '收货人姓名'
            ])
            ->add('consignee_concat', null, [
                'label' => '收货人电话'
            ])
            ->add('consignee_address', null, [
                'label' => '收货人地址'
            ])
            // ->add('deleted_at')
            ->add('getTotalBillByReturn', null, [
                'label' => '退画数量'
            ])
            ->add('getTotalBillByAppend', null, [
                'label' => '增画数量'
            ])
            ->add('total', 'currency', [
                'label' => '押金',
                'currency' => '￥'
            ])
            ->add('getTotalProfit', 'currency', [
                'label' => '押金差价',
                'currency' => '￥'
            ])
            ->add('status', 'choice', [
                'label' => '状态',
                'choices' => OrderType::getReadableValues(),
            ])
            ->add('created_at', null, [
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('updated_at', null, [
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [
                        'template' => 'admin/order_edit_action.html.twig'
                    ]
                ],
            ]);
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('订单信息', [
                'class' => 'col-xs-7'
            ])
                ->add('id')
                ->add('order_number', null, [
                    'label' => '订单号'
                ])
                ->add('status', 'choice', [
                    'label' => '状态',
                    'choices' => OrderType::getReadableValues()
                ])
                ->add('getTotalBillByReturn', null, [
                    'label' => '退画数量'
                ])
                ->add('getTotalBillByAppend', null, [
                    'label' => '增画数量'
                ])
                ->add('total', 'currency', [
                    'label' => '押金',
                    'currency' => '￥'
                ])
                ->add('getTotalProfit', 'currency', [
                    'label' => '押金差价',
                    'currency' => '￥'
                ])
                // ->add('deleted_at')
                ->add('created_at', null, [
                    'format' => 'Y-m-d H:i:s'
                ])
                ->add('updated_at', null, [
                    'format' => 'Y-m-d H:i:s'
                ])
            ->end()
            ->with('用户信息', [
                'class' => 'col-xs-5'
            ]);

        if(!$this->isChild())
        {
            $showMapper->add('consumer', null, [
                'label' => '用户昵称',
                'route' => ['name' => 'show']
            ]);
        }

        $showMapper
            ->add('consignee_name', null, [
                'label' => '收货人姓名'
            ])
            ->add('consignee_concat', null, [
                'label' => '收货人电话'
            ])
            ->add('consignee_address', null, [
                'label' => '收货人地址'
            ])
            ->end()
            ->with('操作时间', [
                'class' => 'col-xs-5'
            ])
            ->add('paid_at', null, [
                'format' => 'Y-m-d H:i:s',
                'label' => '付款时间'
            ])
            ->add('sent_at', null, [
                'format' => 'Y-m-d H:i:s',
                'label' => '发货时间'
            ])
            ->add('took_at', null, [
                'format' => 'Y-m-d H:i:s',
                'label' => '收货时间'
            ])
            ->end()
            ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $order = $this->getSubject();

        if(!OrderActionType::isValueExist($order->getStatus())) {
            $admin = $this->isChild() ? $this->getParent() : $this;
            $response = new RedirectResponse($admin->generateUrl('list'));
            return $response->send();
        }

        $btns = OrderActionType::ACTION_SELECT[$order->getStatus()];

        $formMapper
            ->add('status', ChoiceType::class, [
                'label' => '状态',
                'choices' => array_filter(OrderType::getChoices(), function($value) use($btns) {
                    return in_array($value, $btns);
                }),
            ])
            ;
    }

    public function postUpdate($order)
    {
        if($order->getStatus() !== OrderType::ALREADY_TAKE) return;

        if($order->getTotalProfit() >= 0) return;

        $this->pay_refund
            ->tradeRefund($order->getOrderNumber(), $order->getTotal(), $order->getTotalProfit(), [
                'refund_desc' => '已归还油画'
            ]);
    }

    public function setWechatPayRefund(WechatPayRefund $pay_refunder)
    {
        $this->pay_refund = $pay_refunder;
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

    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {

        $admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get('id');

        if ($childAdmin) {
            $menu->addChild(
                'order_show',
                ['uri' => $admin->generateUrl('show', ['id' => $id])]
            );
        }

        if ($childAdmin || !in_array($action, ['show'])) {
            return;
        }

        $menu->addChild(
            'order_bill_list',
            ['uri' => $admin->generateUrl('app.admin.order_bill.list', ['id' => $id])]
        );

        $menu->addChild(
            'order_edit',
            ['uri' => $admin->generateUrl('edit', ['id' => $id])]
        );
    }
}
