<?php


namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Gedmo\Uploadable\UploadableListener;
use Doctrine\Common\Collections\ArrayCollection;


class FileUploaderAdmin extends AbstractAdmin
{
    protected function getEntitySubject()
    {
        if($this->hasParentFieldDescription()) { // this Admin is embedded
            // $getter will be something like 'getlogoImage'
            $getter = 'get' . $this->getParentFieldDescription()->getFieldName();

            // get hold of the parent object
            $parent = $this->getParentFieldDescription()->getAdmin()->getSubject();
            if ($parent) {
                $isArrayCollection = $parent->$getter() instanceof ArrayCollection;
                $entity = $isArrayCollection ? null : $parent->$getter()->getOwner();
            } else {
                $entity = null;
            }
        } else {
            $entity = $this->getSubject();
        }

        return $entity;
    }

    protected function createPreviewImage($webPath) {
        return '<img src="/'.$webPath.'" class="admin-preview" />';
    }

    public function createPreview($fieldName) {
        $entity = $this->getEntitySubject();

        $methodName = 'get' . ucfirst($fieldName);
        $previewImage = null;
        if ($entity && ($webPath = $entity->$methodName())) {
            // add a 'help' option containing the preview's img tag
            $previewImage = $this->createPreviewImage($webPath);
        }

        return $previewImage;
    }
    // public function setUploadableListener(UploadableListener $listener)
    // {
    //     $this->uploader = $listener;
    // }
}
