<?php 

class Data {
    
    private $s; 
    private $tournaments;
    public $sports;
    public $tikets;
    public $eventlist = array();
    
    function __construct(){
        
        global $s;
        $this->s = $s;
        $this->sports = $this->s->getSports();
        
    }
    
    public function gToutnaments($sp){
        
        $this->tournaments = $this->s->getTournaments($sp);
        return $this->tournaments;
            
    }
    
    public function getTikets($tour){
        
        $this->s->SearchByTournament($tour);
        $this->eventlist = $this->s->getEvents();
        
        return $this->eventlist;
        
    } 
}

?> 