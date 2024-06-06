<?php

namespace App\Utils;

use Pimcore\Model\DataObject;

class ImporterUtils
{
    /**
     * Retrieves an existing Pimcore DataObject from the database or creates a new one if it doesn't exist.
     *
     * @param string $className The class name of the DataObject.
     * @param string $fieldName The field name used to find the DataObject.
     * @param mixed $value The value of the field used to find the DataObject.
     *
     * @return Concrete|null The existing or newly created DataObject, or null if the class doesn't exist or is not a DataObject.
     */
    public function getOrCreate(string $className, string $fieldName, $value)
    {

        if (!class_exists($className)) {
            return null;
        }

       $listingClassName = $className . "\\Listing";
       if(!class_exists($listingClassName)){
        return null;
       }

       $entries = new $listingClassName();
       $escapedFieldName = "`$fieldName`"; // Escape the field name with backticks
        $entries->setCondition("$escapedFieldName = ?", [$value]);
       $entries->setLimit(1);
       $entities = $entries->load();

       if(count($entities) > 0){
        return $entities[0];
       }

       $entity = new $className();
       return $entity;
    }
}





//  Check if the class exists and is a subclass of Pimcore DataObject
// if (!class_exists($className) || !is_subclass_of($className, AbstractObject::class)) {
//     error_log("Class $className does not exist or is not a subclass of Pimcore DataObject.");
//     return null;
// }
// $listingClassName = $className . '\\Listing';
// if (!class_exists($listingClassName)) {
//     error_log("Listing class $listingClassName does not exist.");
//     return null;
// }


// // Create a new listing to find existing objects
// $listing = new $listingClassName();
// $listing->setCondition("$fieldName = ?", [$value]);

// // Try to find an existing object
// $existingObjects = $listing->load();
// if (!empty($existingObjects)) {
//     return $existingObjects[0];
// }

// error_log("No existing $className found with $fieldName = $value. Creating new one.");

// // Create a new object if none found
// return new $className();