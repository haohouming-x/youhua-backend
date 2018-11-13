<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\{TextType, TextareaType, FileType, MoneyType, NumberType};
use Gedmo\Uploadable\UploadableListener;
use App\Admin\FileUploaderAdmin;
use App\Entity\Marketing;


final class MarketingAdmin extends FileUploaderAdmin
{
    protected $classnameLabel = '营销设置';

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', null, [
                'label' => '名称'
            ])
            ->add('original_price', null, [
                'label' => '原价'
            ])
            ->add('present_price', null, [
                'label' => '现价'
            ])
            ->add('discount', null, [
                'label' => '折扣'
            ])
            ->add('validity_date', null, [
                'label' => '天数'
            ])
            ->add('created_at', 'doctrine_orm_date', [
                'label' => '创建时间'
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
                'width' => 125,
                'height' => 125,
            ])
            ->add('name', null, [
                'label' => '名称'
            ])
            ->add('explain_text' , null, [
                'label' => '说明'
            ])
            ->add('original_price', null, [
                'label' => '原价'
            ])
            ->add('present_price', null, [
                'label' => '现价'
            ])
            ->add('discount', null, [
                'label' => '折扣'
            ])
            ->add('validity_date', null, [
                'label' => '天数'
            ])
            ->add('created_at', null, [
                'label' => '创建时间',
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
        $formMapper
            ->add('file', FileType::class, [
                'label' => '图片',
                'help' => $this->createPreview('image')
            ])
            ->add('name', TextType::class, [
                'label' => '名称'
            ])
            ->add('explain_text', TextareaType::class, [
                'label' => '说明'
            ])
            ->end()
            ->with('价格', [
                'class' => 'col-xs-6'
            ])
            ->add('original_price', MoneyType::class, [
                'label' => '原价',
                'currency' => 'CNY'
            ])
            ->add('present_price', MoneyType::class, [
                'label' => '现价',
                'currency' => 'CNY'
            ])
            ->end()
            ->with('基本设置', [
                'class' => 'col-xs-6'
            ])
            ->add('discount', NumberType::class, [
                'label' => '折扣'
            ])
            ->add('validity_date', NumberType::class, [
                'label' => '天数'
            ])
            ->end()
            ;
    }

    public function prePersist($market)
    {
        $this->manageFileUpload($market);
    }

    public function preUpdate($market)
    {
        $this->manageFileUpload($market);
    }

    private function manageFileUpload(Marketing $market)
    {
        if ($fileInfo = $market->getFile()) {
            $this->uploader->addEntityFileInfo($market, new \App\UploadedFileInfo($fileInfo));

            $market->setFile(null);
        }
    }

    public function setUploadableListener(UploadableListener $listener)
    {
        $this->uploader = $listener;
    }

}
