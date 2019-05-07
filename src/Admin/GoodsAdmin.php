<?php
namespace App\Admin;

use Sonata\AdminBundle\Datagrid\{DatagridMapper, ListMapper};
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\Type\{ModelType};
use Sonata\CoreBundle\Form\Type\CollectionType;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;
use Symfony\Component\Form\Extension\Core\Type\{TextType, FileType, IntegerType, NumberType, TextareaType, MoneyType};
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use App\DependencyInjection\UploadableListener;
use App\Admin\FileUploaderAdmin;
use App\Entity\Goods;


final class GoodsAdmin extends FileUploaderAdmin
{
    protected $classnameLabel = '商品';

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', null, [
                'label' => '名称'
            ])
            ->add('classify', null, [
                'label' => '分类'
            ], null, [
                'multiple' => true
            ])
            ->add('stock', 'doctrine_orm_number', [
                'label' => '库存'
            ])
            ->add('market_price', 'doctrine_orm_number', [
                'label' => '市场价',
                'currency' => '￥'
            ])
            ->add('deposit_price', 'doctrine_orm_number', [
                'label' => '押金价',
                'currency' => '￥'
            ])
            ->add('created_at', 'doctrine_orm_date')
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', null,[
                'label' => '名称'
            ])
            ->add('classify', null,  [
                'label' => '商品分类',
                'associated_property' => 'name',
            ])
            ->add('image', 'image', [
                'label' => '图片',
                'prefix' => '/',
                'width' => 65,
                'height' => 65,
            ])
            ->add('stock', 'number', [
                'label' => '库存'
            ])
            ->add('getTotalBill', 'number', [
                'label' => '销量'
            ])
            ->add('market_price', 'currency', [
                'label' => '市场价',
                'currency' => '￥'
            ])
            ->add('deposit_price', 'currency', [
                'label' => '押金价',
                'currency' => '￥'
            ])
            ->add('created_at', null, [
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ]
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper
            ->add('name', TextType::class, [
                'label' => '商品名称'
            ])
            ->add('classify', ModelType::class, [
                'label' => '商品分类',
                'multiple' => true,
                'property' => 'name',
                'btn_add' => false
            ])
            ->add('describes', TextareaType::class, [
                'label' => '商品描述'
            ])
            ->add('file', FileType::class, [
                'label' => '主图',
                'required' => $this->isCurrentRoute('create'),
                'help' => $this->createPreview('image')
            ])
            ->add('pictures', CollectionType::class, [
                'by_reference' => true,
                'btn_add' => '新增',
                'label' => '轮换图'
            ], [
                'edit' => 'inline',
                'inline' => 'table',
                'admin_code' => 'app.admin.goods_banner'
            ])
            ->add('stock', IntegerType::class, [
                'label' => '库存',
                'attr' => [
                    'min' => 0
                ]
            ])
            ->end()
            ->with('价格', [
                'class' => 'col-xs-6'
            ])
                ->add('market_price', MoneyType::class, [
                    'label' => '市场价',
                    'currency' => 'CNY'
                ])
                ->add('deposit_price', MoneyType::class, [
                    'label' => '押金价',
                    'currency' => 'CNY'
                ])
            ->end()
            ->with('尺寸', [
                'class' => 'col-xs-6'
            ])
                ->add('long_size', NumberType::class, [
                    'label' => false,
                    'help'=>'mm'
                    ])
                ->add('wide_size', NumberType::class, [
                    'label' => false,
                    'help'=>'mm'
                    ])
            ->end()
            ->add('details', SimpleFormatterType::class, [
                'label' => '详情',
                'format' => 'richhtml',
                'ckeditor_context' => 'rip_config'
            ])
            ;
    }

    public function prePersist($goods)
    {
        $this->manageFileUpload($goods);
        $this->setGoodsBanner($goods, $goods->getPictures()->getValues());
    }

    public function preUpdate($goods)
    {
        $this->manageFileUpload($goods);
        $this->setGoodsBanner($goods, $goods->getPictures()->getValues());
    }

    private function manageFileUpload($entity)
    {
        if ($fileInfo = $entity->getFile()) {
            $this->uploader->addEntityFileInfo($entity, new \App\UploadedFileInfo($fileInfo));

            $entity->setFile(null);
        }
    }

    private function setGoodsBanner(Goods $goods, Array $banners)
    {
        foreach($banners as $banner)
        {
            $this->manageFileUpload($banner);
            $banner->setGoods($goods);
        }
    }

    public function setUploadableListener(UploadableListener $listener)
    {
        $this->uploader = $listener;
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('stock')
            ->assertLength(['min' => 0])
            ->end();
    }

}
