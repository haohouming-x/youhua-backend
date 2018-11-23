<?php

namespace App\Admin;

use Sonata\AdminBundle\Datagrid\{DatagridMapper, ListMapper};
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Form\Type\{ChoiceFieldMaskType, ModelListType};
use Symfony\Component\Form\Extension\Core\Type\{TextType, FileType, ChoiceType};
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
            ->add('type', 'choice', [
                'label' => '跳转类型',
                'choices' => BannerType::getReadableValues()
            ])
            ->add('created_at', 'datetime', [
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('updated_at', 'datetime', [
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
            ->add('file', FileType::class, [
                'label' => '图片',
                'required' => $this->isCurrentRoute('create'),
                'help' => $this->createPreview('image')
            ])
            ->add('type', ChoiceFieldMaskType::class, [
                'label' => '类型',
                'expanded' => true,
                'choices' => BannerType::getChoices(),
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
        $datagridMapper
            ->add('type', null, [
                'label' => '跳转链接类型',
            ], ChoiceType::class, [
                'choices' => BannerType::getChoices()
            ])
            ->add('created_at', 'doctrine_orm_date');
    }

    public function configureRoutes(RouteCollection $collection) {
        $collection->remove('export');
    }
}
