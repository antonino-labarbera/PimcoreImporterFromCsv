<?php

namespace App\Controller;

use App\Service\CompanyImporterService;
use App\Service\CsvFileReader;
use App\Utils\ImporterUtils;
use Pimcore\Bundle\AdminBundle\Controller\Admin\LoginController;
use Pimcore\Controller\FrontendController;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\Service;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends FrontendController
{
    
    /**
    * @param Request $request
    * @return Response
    */
    public function defaultAction(Request $request): Response
    {
        return $this->render('default/default.html.twig');
    }    
    
    /**
    * Forwards the request to admin login
    */
    public function loginAction(): Response
    {
        return $this->forward(LoginController::class.'::loginCheckAction');
    }
    
    #[Route('/product', name: 'app_product')]
    public function viewObjectAction(CompanyImporterService $companyImporterService): JsonResponse
    {
        
        $csvFilePath = '/var/www/html/public/assets/FileSap.csv';
         
           
                $result = $companyImporterService->importProducts($csvFilePath);
                return new JsonResponse('Companies created', 200);
            
        }
        
        
        
        #[Route('/company', name: 'app_company')]
        public function saveCompanyAction(CompanyImporterService $companyImporterService): JsonResponse
        {   
            $csvFilePath = '/var/www/html/public/assets/gerarchiaMateriali.csv';
         
           
                $result = $companyImporterService->importCompanies($csvFilePath);
                return new JsonResponse('Companies created', 200);
            
        }



    }


    // // Fetch the object by its ID
    // $object = Article::getById($id);
    
    // if (!$object) {
        //     return new JsonResponse(['error' => 'Object not found'], 404);
        // }
        
        // // Prepare the object data as an array
        // $objectData = [
            //     'company_code' => $object->getCompany_code(),
            //     // Add more fields as necessary
            // ];
            
            // // Return the object data as a JSON response
            // return new JsonResponse($objectData);
            
            
            // $article = new Article();
            // $article->setParent(Service::createFolderByPath("/Article/imported"));
            // $article->setKey("imported-article-" . uniqid());





               // $companiesData = $csvFileReader->readFile($csvFilePath);
            
            
            // if (empty($companiesData)) {
            //     return new JsonResponse('No data found in CSV file', 500);
            // }

            // $companies = [];
            // $brands = [];
            // $families = [];
            // $groups = [];
            
            // $counter = 0; 
            // $maxArticles = 6;
            
            // foreach ($companiesData as $data){
            //     if ($counter >= $maxArticles) {
            //         break;}

            //         $companyKey = $data['company_code'];
            //         if (!isset($companies[$data['company_code']])) {
            //     $company = $utils->getOrCreate(Company::class, 'company_code', $data['company_code']);
            //     if (!$company->getId()) {
            //         $company->setParent(Service::createFolderByPath("/Company"));
            //         $company->setKey("company-" . $companyKey);
            //         $company->setPublished(true);
            //         $company->setCompany_code($data['company_code']);
            //         $company->save();
            //     }
            //     $companies[$data['company_code']] = $company;
            // } else {
            //     $company = $companies[$data['company_code']];
            // }
               
            // $brandKey = $data['brand'];
            // if (!isset($brands[$data['brand']])) {
            //     $brand = $utils->getOrCreate(Brand::class, 'brand', $data['brand']);
            //     if (!$brand->getId()) {
            //         $brand->setParent($company);
            //         $brand->setKey("brand-" . $brandKey);
            //         $brand->setPublished(true);
            //         $brand->setBrand($data['brand']);
            //         $brand->save();
            //     }
            //     $brands[$data['brand']] = $brand;
            // } else {
            //     $brand = $brands[$data['brand']];
            // }

            // $familyKey = $data['family'];    
            // if (!isset($families[$data['family']])) {
            //     $family = $utils->getOrCreate(Family::class, 'family', $data['family']);
            //     if (!$family->getId()) {
            //         $family->setParent($brand);
            //         $family->setKey("family-" . $familyKey);
            //         $family->setPublished(true);
            //         $family->setFamily($data['family']);
            //         $family->save();
            //     }
            //     $families[$data['family']] = $family;
            // } else {
            //     $family = $families[$data['family']];
            // }
            // $groupKey = $data['group'];  
            // if (!isset($groups[$data['group']])) {
            //     $group = $utils->getOrCreate(Group::class, 'group', $data['group']);
            //     if (!$group->getId()) {
            //         $group->setParent($family);
            //         $group->setKey("group-" . $groupKey);
            //         $group->setPublished(true);
            //         $group->setGroup($data['group']);
            //         $group->save();
            //     }
            //     $groups[$data['group']] = $group;
            // } else {
            //     $group = $groups[$data['group']];
            // }
                
            //     $counter++;
            // 