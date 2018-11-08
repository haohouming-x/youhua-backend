<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Admin\FileUploaderAdmin;


final class GoodsBannerAdmin extends FileUploaderAdmin
{
    public function createPreview($fieldName) {
        $entity = $this->getSubject();

        $methodName = 'get' . ucfirst($fieldName);
        $previewImage = null;
        if ($entity && ($webPath = $entity->$methodName())) {
            // add a 'help' option containing the preview's img tag
            $previewImage = $this->createPreviewImage($webPath);
        }

        return $previewImage;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('file', FileType::class, [
                'label' => '图片',
                'attr' => [
                    'class' => 'col-xs-3'
                ],
                'help' => $this->createPreview('image')
            ]);
    }
}
