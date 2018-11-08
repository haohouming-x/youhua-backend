<?php
namespace App\Admin;

use Sonata\AdminBundle\Datagrid\{DatagridMapper, ListMapper};
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\Type\{ModelType};
use Sonata\CoreBundle\Form\Type\CollectionType;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Component\Form\Extension\Core\Type\{TextType, FileType, IntegerType, NumberType, TextareaType, MoneyType};
use App\DependencyInjection\UploadableListener;
use App\Admin\FileUploaderAdmin;
use App\Entity\Goods;


final class GoodsAdmin extends FileUploaderAdmin
{

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
            ->add('market_price', 'currency', [
                'label' => '市场价',
                'currency' => '￥'
            ])
            ->add('deposit_price', 'currency', [
                'label' => '押金价',
                'currency' => '￥'
            ])
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
                'label' => '操作'
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
                'help' => $this->createPreview('image')
            ])
            ->add('pictures', CollectionType::class, [
                'by_reference' => true,
                'btn_add' => '新增',
                'label' => '轮换图'
            ], [
                'edit' => 'inline',
                'inline' => 'table',
                'admin_code' => 'admin.goods_banner'
            ])
            ->add('stock', IntegerType::class, [
                'label' => '库存',
                'attr' => [
                    'min' => 0
                ]
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
            ->with('尺寸')
            ->add('long_size', NumberType::class, [
                    'label' => false
                ])
            ->add('wide_size', NumberType::class, [
                    'label' => false
                ])
            ->end()
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

    private function setGoodsBanner(Goods $goods, Array $banners) {
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
