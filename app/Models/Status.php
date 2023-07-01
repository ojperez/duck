<?php

namespace App\Models;

use App\Services\DuckService;

class Status
{
    private $_position;
    private $_alive;
    private $_breathing;
    private $_walking;
    private $_direction;
    
    function __construct(Array $data) {
        $this->_alive     = isset($data['alive']) ? (bool)$data['alive'] : false;
        $this->_breathing = isset($data['breathing']) ? (bool)$data['breathing'] : false;
        $this->_walking   = isset($data['walking']) ? (bool)$data['walking'] : false;
        
        $this->_position  = isset($data['position']) &&
                            is_array($data['position']) && 
                            count($data['position']) == 2 &&
                            isset($data['position'][0]) && is_numeric($data['position'][0]) &&
                            isset($data['position'][1]) && is_numeric($data['position'][1])                
                ? $data['position'] : false;
        
        if (!$this->_position) {
            throw new \Exception("Bad status format: position must be a 2 elements numeric array");
        }
        
        $this->_direction = isset($data['direction']) && in_array(strtoupper($data['direction']), DuckService::$allowedDirections) ? strtoupper($data['direction']) : false;
        
        if (!$this->_direction) {
            throw new \Exception("Bad status format: direction must be one of these values: N, S, W, E");
        }
    }
    
    public function getPosition()
    {
        return $this->_position ? '[X: '.$this->_position[0].', Y: '.$this->_position[1].']' : 'Position not set.';
    }
    
    public function getAlive()
    {
        return $this->_alive ? "Yes" : "No";
    }
    
    public function getBreathing()
    {
        return $this->_breathing ? "Yes" : "No";
    }
    
    public function getWalking()
    {
        return $this->_walking ? "Yes" : "No";
    }
    
    public function getDirection()
    {
        switch ($this->_direction) {
            case 'N': 
                return 'North';
                break;
            case 'S': 
                return 'South';
                break;
            case 'E': 
                return 'East';
                break;
            case 'W': 
                return 'West';
                break;
            default:
                return 'Unknown';
        }
    }
    
}