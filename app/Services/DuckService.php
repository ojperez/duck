<?php

namespace App\Services;

/**
 * Class to perform basic operations on our duck
 */
class DuckService
{
    private $_dataService;
    private $_status;
    
    
    public static $allowedDirections = ['N', 'S', 'W', 'E'];
    private static $_duckSpeed = 1; //1 "unit" per second
    
    function __construct(DataService $dataService) {
        $this->_dataService = $dataService;
        $this->_status = $this->getStatus();
    }
    
    
    public function getStatus() 
    {
        $this->_status = $this->_dataService->fetchData();
        //Empty storage, let's initialize it
        if ($this->_status === false) {
            $this->_status = $this->_initialStatus();
            $this->persistData();            
        }
        
        if ($this->isAlive() && $this->_status['walking']) {
            $distance = intval((time() - $this->_status['_lastTime']) * static::$_duckSpeed);
            switch($this->_status['direction']) {
                case 'N':
                    $this->_status['position'][0] += $distance;
                    break;
                case 'S':
                    $this->_status['position'][0] -= $distance;
                    break;
                case 'E':
                    $this->_status['position'][1] += $distance;
                    break;
                case 'W':
                    $this->_status['position'][1] -= $distance;
                    break;
            }
        }
        
        //Kill duck if not breathing for longer than a minute
        if ($this->_status['alive'] && !$this->_status['breathing'] && (time() - $this->_status['_lastTime'] > 60)) {
            $this->kill();            
            return $this->getStatus();
        }
        
        
        return $this->_status;
    }
    
    private function isAlive()
    {
        return is_array($this->_status) && isset($this->_status['alive']) && $this->_status['alive'] === true;
    }
    
    private function persistData()
    {
        $data = $this->_status;
        $data['_lastTime'] = time();
        $this->_dataService->persistData($data);
        
    }
    
    //Default state for our duck
    protected function _initialStatus()
    {
        return [
            'position'  => [0, 0],
            'alive'     => false,
            'breathing' => false,
            'walking'   => false,
            'direction' => 'N',
            '_lastTime' => null,
        ];
    }

    public function hatch() 
    {
        if (!$this->isAlive()) {
            $this->_status['alive'] = true;
            $this->_status['breathing'] = true;
            $this->_status['walking'] = false;
            $this->persistData();
            
            return true;
        }
        
        return "Duck was already alive, can't hatch.";
    }


    public function kill() 
    {
        if ($this->isAlive()) {
            $this->_status['alive'] = false;
            $this->_status['breathing'] = false;
            $this->_status['walking'] = false;
            $this->persistData();
            
            return true;
        }
        
        return "Duck was already dead, can't kill.";
    }


    public function startBreathing() 
    {
        if (!$this->isAlive()) {            
            return "Can't start breathing, duck is dead.";
        }
        
        if ($this->_status['breathing']) {            
            return "Can't start breathing, already breathing";
        }
        
        
        $this->_status['breathing'] = true;
        $this->persistData();
            
        return true;        
    }


    public function stopBreathing() 
    {
        if (!$this->isAlive()) {            
            return "Can't stop breathing, duck is dead.";
        }
        
        if (!$this->_status['breathing']) {            
            return "Can't stop breathing, already not breathing";
        }
        
        
        $this->_status['breathing'] = false;
        $this->persistData();
            
        return true;  
    }


    public function startWalking() 
    {
        if (!$this->isAlive()) {            
            return "Can't start walking, duck is dead.";
        }
        
        if ($this->_status['walking']) {            
            return "Can't start walking, already walking";
        }
        
        
        $this->_status['walking'] = true;
        $this->persistData();
            
        return true;  

    }

    public function stopWalking() 
    {
        if (!$this->isAlive()) {            
            return "Can't stop walking, duck is dead.";
        }
        
        if (!$this->_status['walking']) {            
            return "Can't stop walking, already not walking";
        }
        
        
        $this->_status['walking'] = false;
        $this->persistData();
            
        return true;  
    }
    
    public function turn($direction)
    {
        if (!$this->isAlive()) {            
            return "Can't turn, duck is dead.";
        }
        
        if (!in_array(strtoupper($direction), static::$allowedDirections)) {
            return "Unknown direction: ".$direction.'. Valid directions are: '.implode(", ", static::$allowedDirections);
        }
        
        $this->_status['direction'] = strtoupper($direction);
        $this->persistData();
            
        return true;  
    }
}