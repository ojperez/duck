<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DuckService;
use App\Models\Status;

class ManageDuck extends Command
{
    protected $_duckService;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'duck:manage {action} {direction?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command used to manage our duck. See documentation for usage instructions.';            
            
      
    function __construct(DuckService $duckService) {        
        parent::__construct();
        $this->_duckService = $duckService;
    }
    

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        if ($action == null || $action == false) {
            $this->error(" Action argument is required.");
            return 0;
        }
        
        try {
            switch ($action) {
                case 'status' :        
                    
                        $statusData = $this->_duckService->getStatus();
                        $status = new Status($statusData);
                        $this->newLine(2);
                        $this->line("Current Duck Status: ");
                        $this->table(
                            ['Position', 'Alive?', 'Breathing?', 'Walking?', 'Direction'],
                            [[$status->getPosition(), $status->getAlive(), $status->getBreathing(), $status->getWalking(), $status->getDirection()]]
                        );
                        
                    break;                
                    
                case 'breathe' :
                    
                        $result = $this->_duckService->startBreathing();
                        if ($result === true) {
                            $this->info("Duck is now breathing!");
                        } else {
                            $this->error($result);
                        }                        
                        
                    break;
                    
                case 'stop-breathing' :
                    
                        $result = $this->_duckService->stopBreathing();
                        if ($result === true) {
                            $this->info("Duck stopped breathing!");
                        } else {
                            $this->error($result);
                        }                        
                        
                    break; 
                    
                case 'walk' :
                    
                        $result = $this->_duckService->startWalking();
                        if ($result === true) {
                            $this->info("Duck is now walking!");
                        } else {
                            $this->error($result);
                        }                        
                        
                    break;
                    
                case 'stop' :
                    
                        $result = $this->_duckService->stopWalking();
                        if ($result === true) {
                            $this->info("Duck stopped walking!");
                        } else {
                            $this->error($result);
                        }                        
                        
                    break; 
                
                case 'kill' :
                    
                        $result = $this->_duckService->kill();
                        if ($result === true) {
                            $this->info("The duck is dead!");
                        } else {
                            $this->error($result);
                        }                        
                        
                    break; 
                
                case 'hatch' :
                    
                        $result = $this->_duckService->hatch();
                        if ($result === true) {
                            $this->info("The duck is now alive!");
                        } else {
                            $this->error($result);
                        }                        
                        
                    break; 
                case 'turn' :
                        $direction = $this->argument('direction');
                        if ($direction == false || $direction == null) {
                            $this->error("Missing direction parameter. Where do you want the duck turning to?");
                            return 0;
                        }
                        $result = $this->_duckService->turn($direction);
                        if ($result === true) {
                            $this->info("The duck is now facing ".$direction);
                        } else {
                            $this->error($result);
                        }                        
                        
                    break; 
                default:
                    $this->error('Unknown action: '.$action);
                    return 0;
                    break;
            }
        } catch (Exception $ex) {
            $this->error($ex->getMessage());
            return 0;
        }        
    }
}
