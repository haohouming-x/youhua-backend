<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\{TextType, FileType};
use Gedmo\Uploadable\UploadableListener;
use App\Entity\ClassifyGoods;
use App\Admin\FileUploaderAdmin;


final class ClassifyGoodsAdmin extends FileUploaderAdmin
{
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'position',
    );

    /**
     * Add sortable route
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('move', $this->getRouterIdParameter().'/move/{position}');
    }

    // protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    // {
    //     $datagridMapper
    //         ->add('name')
    //         ;
    // }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name', null, [
                'label' => '名称'
            ])
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
                    'move' => [
                        'template' => '@PicossSonataExtraAdmin/CRUD/list__action_sort.html.twig',
                        'hide_label' => false, // Hide button text, default to true
                    ]
                ],
                'label' => '操作'
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', TextType::class, [
                'label' => '名称'
            ])
            ->add('file', FileType::class, [
                'label' => '图片',
                'help' => $this->createPreview('image')
            ]);
    }

    public function prePersist($classifyGoods)
    {
        $this->manageFileUpload($classifyGoods);
    }

    public function preUpdate($classifyGoods)
    {
        $this->manageFileUpload($classifyGoods);
    }

    private function manageFileUpload(ClassifyGoods $classifyGoods)
    {
        if ($fileInfo = $classifyGoods->getFile()) {
            $this->uploader->addEntityFileInfo($classifyGoods, new \App\UploadedFileInfo($fileInfo));

            $classifyGoods->setFile(null);
        }
    }

    public function setUploadableListener(UploadableListener $listener)
    {
        $this->uploader = $listener;
    }
}
