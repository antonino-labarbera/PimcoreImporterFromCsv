<?php

namespace App\Service;

use Pimcore\Model\DataObject\Company;
use Pimcore\Model\DataObject\Brand;
use Pimcore\Model\DataObject\Family;
use Pimcore\Model\DataObject\Group;
use App\Utils\ImporterUtils;
use Pimcore\Model\DataObject\Service;
use App\Service\CsvFileReader;
use Pimcore\Model\DataObject\Product;

class CompanyImporterService{

    private $csvFileReader;
    private $utils;

    public function __construct(CsvFileReader $csvFileReader, ImporterUtils $utils )
    {
       $this->csvFileReader = $csvFileReader;
       $this->utils = $utils; 
    }

 

    public function ImportCompanies(string $csvFilePath){
        $companiesData = $this->csvFileReader->readFile($csvFilePath);

        if (empty($companiesData)) {
            throw new \Exception('No data found in CSV file');
        }

       foreach ($companiesData as $data){
        $this->processCompanyData($data);
       }
    }

    private function processCompanyData(array $data){

        $companyKey = $data['company_code'];
        $companyFolder = Service::createFolderByPath("/Company");
        $company = $this->setFields(Company::class, 'company_code', $companyKey, $companyFolder, $data);

        $brandKey = $data['brand'];
        $brand = $this->setFields(Brand::class, 'brand', $brandKey, $company, $data);

        if (!empty($data['family']) && !empty($data['group'])) {
            
            $familyKey = $data['family'];
            $family = $this->setFields(Family::class, 'family', $familyKey, $brand, $data);
    
            $groupKey = $data['group'];
            $group = $this->setFields(Group::class, 'group', $groupKey, $family, $data);
        }
    }


    private function setFields($className, $fieldName, $key,$parent = null, $data = []){

        if (empty($key)) {
            throw new \Exception('Key cannot be empty');
        }

        if ($parent && !$parent instanceof \Pimcore\Model\Element\ElementInterface) {
            throw new \InvalidArgumentException('Parent must be an instance of Pimcore\Model\Element\ElementInterface');
        }

        $entity = $this->utils->getOrCreate($className, $fieldName, $key);
        $new = !$entity->getId();

        if($new){
            if($parent){
                $entity->setParent($parent);
            }else {
                $entity->setParent(Service::createFolderByPath("/" . ucfirst($className)));
            }
            $entity->setPublished(true);
        }


        $entity->setKey($key);

        if(!empty($data)){
            foreach($data as $field => $value){
                $setterMethod = "set" . ucfirst($field);
                if(method_exists($entity, $setterMethod)){
                    $entity->$setterMethod($value);
                }
            }
        }
        $entity->save();
        return $entity;
    }

    public function importProducts(string $csvFilePath)
    {
        $productsData = $this->csvFileReader->readFile($csvFilePath);

        if (empty($productsData)) {
            throw new \Exception('No data found in CSV file');
        }

        foreach ($productsData as $data) {
            $this->processProductData($data);
        }
    }

    private function processProductData(array $data)
    {
        if($data['company_code'] == 'Z001'){

            $productKey = $data['material'];  
            $groupKey = $data['group'];
            $group = $this->utils->getOrCreate(Group::class, 'group', $groupKey);
            if (!$group) {
                throw new \Exception("Group with key {$groupKey} not found");
            }
    
            $product = $this->setFields(Product::class, 'material', $productKey, $group, $data);
        }
    }
    }




   // private function getOrCreateObject(string $className, string $keyField, string $keyValue, ModelDataObject $parent = null)
    // {
    //     $object = $this->utils->getOrCreate($className, $keyField, $keyValue);
    //     if (!$object->getId()) {
    //         if ($parent) {
    //             $object->setParent($parent);
    //         } else {
    //             $object->setParent(Service::createFolderByPath("/" . $className));
    //         }
    //         $object->setKey(strtolower($className) . "-" . $keyValue);
    //         $object->setPublished(true);
    //     }
    //     return $object;
    // }
