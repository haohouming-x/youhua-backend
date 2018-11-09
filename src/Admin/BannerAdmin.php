<?php

namespace App\Admin;

use Sonata\AdminBundle\Datagrid\{DatagridMapper, ListMapper};
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\Type\{ChoiceFieldMaskType, ModelListType};
use Symfony\Component\Form\Extension\Core\Type\{TextType, FileType};
use Gedmo\Uploadable\UploadableListener;
use App\Admin\FileUploaderAdmin;
use App\DBAL\Types\BannerType;
use App\Entity\Banner;


final class BannerAdmin extends FileUploaderAdmin
{
    protected $classnameLabel = '轮播图';

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('image', 'image', [
                'label' => '图片',
                'prefix' => '/',
                'width' => 125,
                'height' => 125,
            ])
            ->add('created_at', 'datetime', [
                'label' => '创建时间',
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('updated_at', 'datetime', [
                'label' => '修改时间',
                'format' => 'Y-m-d H:i:s'
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
            ->add('file', FileType::class, [
                'label' => '图片',
                'help' => $this->createPreview('image')
            ])
            ->add('type', ChoiceFieldMaskType::class, [
                'label' => '类型',
                'expanded' => true,
                'choices' => BannerType::getChoices(),
                'empty_data' => BannerType::LINK,
                'map' => [
                    BannerType::GOODS => ['goods'],
                    BannerType::LINK => ['link'],
                ]
            ])
            ->add('link', TextType::class, [
                'label' => '链接',
                'required' => false
            ])
            ->add('goods', ModelListType::class, [
                'by_reference' => true,
                'label' => '商品选择',
                'btn_list' => '选择',
                'btn_delete' => '移除',
                'btn_edit' => false,
                'btn_add' => false
            ]);
    }

    public function prePersist($banner)
    {
        $this->manageFileUpload($banner);
    }

    public function preUpdate($banner)
    {
        $this->manageFileUpload($banner);
    }

    private function manageFileUpload(Banner $banner)
    {
        if ($fileInfo = $banner->getFile()) {
            $this->uploader->addEntityFileInfo($banner, new \App\UploadedFileInfo($fileInfo));

            $banner->setFile(null);
        }
    }

    public function setUploadableListener(UploadableListener $listener)
    {
        $this->uploader = $listener;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        // $datagridMapper->add('name');
    }

}
