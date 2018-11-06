<?php
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\{DatagridMapper, ListMapper};
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\Type\{ChoiceFieldMaskType, ModelListType};
use Sonata\CoreBundle\Form\Type\CollectionType;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Component\Form\Extension\Core\Type\{TextType, FileType, IntegerType};
use Symfony\Component\Form\{FormEvent, FormEvents};
use App\DBAL\Types\BannerType;
use App\Entity\Banner;


class GoodsAdmin extends AbstractAdmin
{
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', TextType::class, [
                'label' => '商品名称'
            ])
            ->add('stock', IntegerType::class, [
                'label' => '库存',
                'attr' => [
                    'min' => 0
                ]
            ]);
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('stock')
               ->assertLength(['min' => 0])
            ->end();
    }

}
