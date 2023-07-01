<?php

namespace App\Services;


/**
 * Simple service class to persist and retrieve serialized data in a flat file
 */
class DataService
{
    private $_file;
    
    function __construct() {
        $this->_file = config('app.flatfile');
        //Attempt to create the file if it doesn't exist, error out if not possible
        if (!file_exists($this->_file)) {            
            if (!touch($this->_file)) {
                $this->_fileNotFound();    
            }            
        }
    }

    /**
     * Fetch stored data from the flat file and return it as an associative array
     * @return bool|Array false on empty file, data array on success
     * @throws \Exception When unable to decode fetched data
     */
    public function fetchData()
    {
        $content = file_get_contents($this->_file);
        if ($content === false) {
            $this->_fileNotFound();
        }
        
        //Return false on empty data
        if ($content === '') {
            return false;
        }        
        
        try {
            $decoded = json_decode($content, true);
        } catch (Exception $ex) {
            throw new \Exception('Data integrity issue. Unable to decode stored data, it might have been corrupted.');
        }
        
        return $decoded;
    }
    
    /**
     * Persists given array data as a JSON object in the flat file
     * @param Array $data
     * @throws \Exception
     */
    public function persistData(Array $data)
    {
        if (!file_put_contents($this->_file, json_encode($data))) {
            throw new \Exception('Unable to persist data. Please make sure the flat file is writable.');
        }
    }
    
    
    private function _fileNotFound()
    {
        throw new \Exception('Unable to open data flat file: '. $this->_file.'. Please check your .env file and make sure FLATFILE_PATH is set to a writable location/file.');
    }
}


