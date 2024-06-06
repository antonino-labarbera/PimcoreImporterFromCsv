<?php

namespace App\Service;

/**
 * Class CsvFileReader
 *
 * A class for reading CSV files.
 */
class CsvFileReader{
    
    /**
     * Reads the contents of a CSV file and returns an array of data sets.
     *
     * @param string $excelFullPath The full path to the CSV file to be read.
     *
     * @return array An array containing data sets extracted from the CSV file.
     */
    public function readFile($csvFullPath): array{
        $booksData = [];
        
        error_log('Attempting to open file: ' . $csvFullPath);

        if(($file = fopen($csvFullPath, 'r')) !== false){
            error_log('File opened successfully.');
        $columns = fgetcsv($file);
        
        foreach ($columns as $column) {
            $column = mb_strtolower(trim($column));
        }

        
        while(($data = fgetcsv($file)) !==false){
            $rowData = [];
            foreach ($data as $key => $value) {
                if(isset($columns[$key])){
                    
                    $rowData[$columns[$key]] = trim($value);
            
                    
                }
            }
            if(!empty($rowData)){
                $booksData[] = $rowData ;
            }
        }
        fclose($file);
        error_log('File processing completed.');
        }else {
            error_log('Failed to open file.');
        }
        return $booksData;
    }
}