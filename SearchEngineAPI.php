<?php
/**
 * SearchEngineAPI
 * 
 * @author Sports Events 365 R&D Team
 * @copyright Sports Events 365
 * @version NOV 3, 2014
 * @access public
 * 
 * 
 * 
 * 
 * 
 * 
 **/

class SearchEngineAPI{
       
    public $username;
    public $password;
    public $xml;
    public $url;
    private $tagLevel = array(); 
    private $context;
    
    /**
     * Vars of arrays for every level combined  
     **/
     
    public $eventlist = array();
    public $catalogs = array();
    public $shipTypes = array();
    public $areas = array();
    
    /**
     * Vars that help to nevigate the XML Object
     **/  

    private $eventindex;
    private $catalogindex;
    private $shippingTypeindex;
    private $rateslistshippingareasindex;
    private $sDataTypeRequested;
    
    function __construct(){
        
        $this->context = stream_context_create(array(
            'http' => array(
             'header'  => "Authorization: Basic " . base64_encode("victor:bredis131313")
            )
        ));  
        
    }
    
    private function init(){
     
        $this->eventlist = $this->getev($this->xml);
        $this->createCatalogArray($this->eventlist);
        $this->createShippTypeArray($this->catalogs);
        $this->createAreaRatesArray($this->shipTypes);
    } 
    
    
    /**
     * getters for events,catalogs,shippingtypes and rates list araes
     **/
     
    public function getEvents(){
        return $this->eventlist;        
    }
    
    public function getCatalogs(){
        return $this->catalogs;
    }
    
    public function getshipTypes(){
        return $this->shipTypes;
    }
    
    public function getareas(){
        return $this->areas;
    }    
    
    /**
     * This function gets an XML Object and returns a pointer to the events
     * @param xmlroot
     * @return Events List
     **/
     
    private function getev(){
        
        $xml = $this->xml;
        $events = $xml->data->item;
        if(count($events)>0)
            return $events;
        else
           {
           //echo "<br />There are no events!!!";
           return $events;
           } 
    }
    /**
     * This function create an eventd id list
     * @return eventlist
     **/ 
     
     public function getEventids(){
        
        $events = $this->xml->data->item;
        $eventsidList = array();
        
        for($i=0;$i<count($events);$i++){
            //echo "<br />".$events[$i]->id;
            $eventsidList[$i] = (string)$events[$i]->id;
        }
        return $eventsidList;
    }
                                                    /*********************************************************/
                                                    /****************STATIC DATA *****************************/
                                                    /*********************************************************/
                                                    /*********************************************************/    
        /**
        * This function create an array with the static data ids and captions.
        * @return array $aStaticData   
        */ 
        private function GetStaticDataIDs()
        {
            $aStaticData = array();        
            $StaticData = $this->xml->data->item;
            $this->xml = "";
			$count = count($StaticData);
			if($count)
			{
	            for($i = 0;$i < $count; $i++)
	            {
	                 $temp = (string)$StaticData[$i]->id;
	                 $aStaticData[(int)$temp] = (string)$StaticData[$i]->caption;
	            }
			}
            return $aStaticData;
        }
        
        /**
        * this function returns the sport type ids
        * @return array
        * @since 02.10.2014
        */
        public function getSports()
        {  
            //creates an xml with the sport type ids and captions. 
            $url = "http://widgets.sportsevents365.com/data/tickets/v2.0/events/?q=getSports";
            $this->url = $url;
            $str = file_get_contents($url, false, $this->context);
            $this->xml = simplexml_load_string($str, 'SimpleXMLElement',  LIBXML_NOCDATA);                 
            
            
                return $this->GetStaticDataIDs();
         
        }
        
        /**
        * this function returns an array with the Tournaments ids by the requested $sportTypeID
        * @param int $sportTypeID
        * @return array
        * @since 02.10.2014
        */        
        public function getTournaments($sportTypeID = 0)
        {
            if($sportTypeID)
            {
                //creates an xml with the tournaments of the requested sporttype id.  
                $url = "http://widgets.sportsevents365.com/data/tickets/v2.0/events/?q=getTournaments&sporttypeid=$sportTypeID";
                $this->url = $url;
                $str = file_get_contents($url, false, $this->context);
                $this->xml = simplexml_load_string($str, 'SimpleXMLElement',  LIBXML_NOCDATA);                
                
            }
                return $this->GetStaticDataIDs();
        }
        
        /**
        * this function returns the subframe ids and captions.
        * @param $tournamentid
        * @return array
        * @since 01.10.2014
        */         
        public function getSubframes($tournamentid = 0)
        {
            //Creates an xml with the subframe events for the requested tournaments 
            if($tournamentid)
            {
                $url = "http://widgets.sportsevents365.com/data/tickets/v2.0/events/?q=getSubframes&tournamentid=$tournamentid";
                $this->url = $url;
                $str = file_get_contents($url, false, $this->context);
                $this->xml = simplexml_load_string($str, 'simpleXMLElement',  LIBXML_NOCDATA);
                $this->sDataTypeRequested = "subframes";
                           
            }
            
                 return $this->GetStaticDataIDs();
        }
        
        /**
        * this function returns the cometitors ids and captions.
        * @param int $sportTypeID
        * @param int $tournamentid 
        * @since 01.10.2014
        */ 
        public function getCompetitors($sportTypeID = 0,$tournamentid = 0)
        {
            //creates an xml with the competitors ids by their sporttype or by the tournaments
            if($sportTypeID)        
                $url = "http://widgets.sportsevents365.com/data/tickets/v2.0/events/?q=getCompetitors&sporttypeid=$sportTypeID";
            else        
                $url = "http://widgets.sportsevents365.com/data/tickets/v2.0/events/?q=getCompetitors&tournamentid=$tournamentid";            
            
            if($sportTypeID || $tournamentid)
            {       
                $this->url = $url;
                $str = file_get_contents($url, false, $this->context);
                $this->xml = simplexml_load_string($str, 'simpleXMLElement', LIBXML_NOCDATA);            
                
            }                
                return $this->GetStaticDataIDs();
            
        }
        
        /**
        * this function returns an array of countries ids and captions.
        * @retun array
        * @since 01.10.2014
        */ 
        public function getCountries()
        {
            //creates an XML with the countries ids and captions
            $url = "http://widgets.sportsevents365.com/data/tickets/v2.0/events/?q=getCountries";
            $this->url = $url;
            $str = file_get_contents($url, false, $this->context);
            $this->xml = simplexml_load_string($str, 'simpleXMLElement', LIBXML_NOCDATA);
            
            
          return $this->GetStaticDataIDs();
            
        }
        
        /**
        * this function returns the cities ids and captions.
        * @param int $countryID
        * @return array 
        * @since 02.10.2014
        */ 
        public function getCities($countryID = 0)
        {
            if($countryID)
            {
                //creates an XML with the Cities details according to the requested country id.
                 
                $url = "http://widgets.sportsevents365.com/data/tickets/v2.0/events/?q=getCities&countryid=$countryID";
                $this->url = $url;
                $str = file_get_contents($url, false, $this->context);
                $this->xml = simplexml_load_string($str, 'simpleXMLElement', LIBXML_NOCDATA);         
                
            }
            
           return $this->GetStaticDataIDs();
        }
       
                                                    /*********************************************************/
                                                    /****************STATIC DATA END**************************/
                                                    /*********************************************************/
                                                    /*********************************************************/     
   
    
    /**
     * this function gets a pointer to the events and event id ,
     * return an index of the correct event
     * @param eventslist
     * @param eventid
     * @return index
     **/ 
     
    private function getEventIndex($events,$id){
        
        $index = -1;
        for($i=0;$i<count($events);$i++){
            if($events[$i]->id == $id){
                $index = $i;
                return $i;
                }
        }
        if($index == -1){
           //echo "<br />The event was not found!!!";
           return;
           }
    }
    /**
     * This function gets a catalog id and returns an event id
     * @param catalogid
     * @return eventid
     **/
     public function getEventIdByCatalogId($catalogId){
        
        for($i=0;$i<count($this->eventlist);$i++){
            for($j=0;$j<count($this->eventlist[$i]->ticketdata->ticketdataitem);$j++){
                if($catalogId == $this->eventlist[$i]->ticketdata->ticketdataitem[$j]->ItemID)
                   $eventId = $this->eventlist[$i]->id;
            }
        }
        return $eventId;
     }
    
    /**
     * This function gets a pointer to the events and Event Index ,
     * then returns a pointer to the catalogs of this event
     * @param eventlist
     * @param index
     * @return cataloglist
     **/
    
     public function getcatalogsbyeventId($eventid){
        
        $index = $this->getEventIndex($this->eventlist,$eventid);
        $catalogs = $this->eventlist[$index]->ticketdata->ticketdataitem;
        //echo "<br />Num of catalog to this event : ".count($events[$index]->ticketdata->ticketdataitem);
        if(count($catalogs)>0)
            return $catalogs;
        else{
            //echo "<br />There are no catalogs!!!";
            return $catalogs;
        }
           
     }
    /**
    * This function get catalog list and returns a catlaog id list
    * @param catalogs
    * @return catalogIdList
    **/
    public function getcatalogIdList($catalogs){
        
        $catalogidList = array();
        for($i=0;$i<count($catalogs);$i++){
            $catalogidList[$i] = (string)$catalogs[$i]->ItemID;
        }
        
        return $catalogidList;
    }  
        
    /**
     * this function gets a pointer to the catalogs and catalog id ,
     * return an index of the correct event
     * @param cataloglist
     * @param catalogid
     * @return index
     **/ 
    private function getCatalogIndex($catalogs,$catalogId){
        
        $index = -1;
        for($i=0;$i<count($catalogs);$i++){
    
            if($catalogs[$i]->ItemID == $catalogId){
                $index = $i;
                return $i;
                }
        }
        if($index == -1){
           //echo "<br />The Catalog was not found!!!";
           return;
           }
    }
    /**
     * This function gets a pointer to the catalog and catalog Index ,
     * then returns a pointer to the shipping methods of this catalog
     * @param cataloglist
     * @param index
     * @return shippingtypelist 
     **/
     private function getShippingTypesByCatalog($catalogid){
        
        $index = $this->getCatalogIndex($catalogs,$catalogid);
        $shippingTypes = $this->catalogs[$index]->shipping_methods->ShippingType;
        //echo "<br />The num of shipping type to this catalog is : ".count($catalogs[$index]->shipping_methods->ShippingType);
        if(count($shippingTypes)>0)
            return $shippingTypes;
        else{
            //echo "<br />The are no shipping types!!!";
            return $shippingTypes;
        }
     }
     
     /**
      * This function gets catalog id and index returns an array of provship id's
      * @param catalogid
      * @param indexofcatalog
      * @return provshipidlist
      **/
      public function getProvshipIdListBycatalog($catalogid){
        
        $index = $this->getCatalogIndex($this->catalogs,$catalogid);
        $provshipList = array();
        $shippingTypes =  $this->catalogs[$index]->shipping_methods->ShippingType;
        
        //echo "<br />The num of shipping type to this catalog is : ".count($catalogs[$index]->shipping_methods->ShippingType);
        for($i=0;$i<count($shippingTypes);$i++){
            $provshipList[$i] = (string)$shippingTypes[$i]->provshipid;
        }
        return $provshipList;
    }
       
     /**
     * this function gets a pointer to the shipping type and provship id ,
     * return an index of the correct shipping type
     * @param shippingtypelist
     * @param provship
     * @return index
     **/ 
    private function getShippingTypeIndex($shippingTypes,$provShipId){
        
        $index = -1;
        for($i=0;$i<count($shippingTypes);$i++){
                if($shippingTypes[$i]->provshipid == $provShipId){
                $index = $i;  
                return $i;
                }
        }
        if($index == -1){
           //echo "<br />The shipping Type was not found!!!";
           return;
           } 
    }
    
    /**
     * This function gets a pointer to the shipping type and shipping type Index ,
     * then returns a pointer to the Rateslistshippingareas of this shipping Type
     * @param shippingTypeslist
     * @param index
     * @return Rateslistshippingareas
     **/
     public function getRateslistByShippingType($provshipid){
        
        $index = $this->getShippingTypeIndex($this->shipTypes,$provshipid);
        $Rateslistshippingareas = $this->shipTypes[$index]->provship_cost->Rateslistshippingareas;
        //echo "<br />The num of Rateslistshippingareas to this shipping Type is : ".count($shippingTypes[$index]->provship_cost->Rateslistshippingareas);
        if(count($Rateslistshippingareas)>0)
            return $Rateslistshippingareas;
        else{
            //echo "<br />There are no Rates!!!";
            return $Rateslistshippingareas;
        }
     }
     
     /**
     * this function gets a pointer to the Rates list shipping areas and area id ,
     * return an index of the correct Rates list shipping areas
     * @param Rateslistshippingareas
     * @param areaid
     * @return index
     **/ 
    private function getRateslistshippingareasIndex($Rateslistshippingareas,$AreaID){
        
        $index = -1;
        for($i=0;$i<count($Rateslistshippingareas);$i++){
                if($Rateslistshippingareas[$i]->areaid == $AreaID){
                $index = $i;
                return $i;
                }
        }
        if($index == -1){
           //echo "<br />The area was not found!!!";
           return;
           } 
    }
    /**
     * This function create the tag level array
     **/      
    private function creatTagArray(){
        
        $eventlist = $this->eventlist;
        $cataloglist = $eventlist->ticketdata->ticketdataitem;
        $shippingType = $cataloglist->shipping_methods->ShippingType;
        $rateslistshippingareas = $shippingType->provship_cost->Rateslistshippingareas;
        
        $children = $eventlist->children();
        foreach($children as $tag=>$val){
            //echo "<br />The tag is : <b>$tag</b>, The val is : <b>$val</b>";
            $this->tagLevel["$tag"]= 1;
        }
        $children = $cataloglist->children();
        foreach($children as $tag=>$val ){
            //echo "<br />The tag is : <b>$tag</b>, The val is : <b>$val</b>";
            $this->tagLevel["$tag"]= 2;
        }
        $children =$shippingType ->children();
        foreach($children as $tag=>$val ){
            //echo "<br />The tag is : <b>$tag</b>, The val is : <b>$val</b>";
            $this->tagLevel["$tag"]= 3;
        }
        $children = $rateslistshippingareas->children();
        foreach($children as $tag=>$val ){
            //echo "<br />The tag is : <b>$tag</b>, The val is : <b>$val</b>";
            $this->tagLevel["$tag"]= 4;
        }
        //var_dump($this->tagLevel);
    }
    
    /**
     * This function create the catalog array for the all events all together
     * @param eventlist
     **/ 
     private function createCatalogArray($events){
        
        $count = 0;
        for($i=0;$i<count($events);$i++){
            //echo "<br />Num of cat in event : ".count($events[$i]->ticketdata->ticketdataitem);
            for($j=0;$j<count($events[$i]->ticketdata->ticketdataitem);$j++){ 
                $this->catalogs[$count++] = $events[$i]->ticketdata->ticketdataitem[$j];
            }
        }
        //var_dump($this->catalogs);
     }
    
    /**
     * This function create the shipping type array for the all catalogs all together
     * @param catalogslist
     **/ 
     private function createShippTypeArray($catalogs){
        
        $count = 0;
        for($i=0;$i<count($catalogs);$i++){
            //echo "<br />Num of shiptype in catalog : ".count($catalogs[$i]->shipping_methods->ShippingType);
            for($j=0;$j<count($catalogs[$i]->shipping_methods->ShippingType);$j++){ 
                $this->shipTypes[$count++] = $catalogs[$i]->shipping_methods->ShippingType[$j];
            }
        }
        //var_dump($this->shipTypes);
     }
    
    /**
     * This function create the area rate array for all the shiping types all together
     * @param shipppingtypelist
     **/ 
     private function createAreaRatesArray($shipTypes){
        
        $count = 0;
        for($i=0;$i<count($shipTypes);$i++){
            //echo "<br />Num of areas in ship type : ".count($shipTypes[$i]->provship_cost->Rateslistshippingareas);
            for($j=0;$j<count($shipTypes[$i]->provship_cost->Rateslistshippingareas);$j++){ 
                $this->areas[$count++] = $shipTypes[$i]->provship_cost->Rateslistshippingareas[$j];
            }
        }
        //var_dump($this->areas);
     }
    
    
    /**
     * This function gets Event id and tag name, then returns a value
     * @param eventid
     * @param tagname
     * @return value 
     **/ 
     public function getValByEventIdAndTag($id,$tag){
        
        $index = $this->getEventIndex($this->eventlist,$id);   
        $value = $this->eventlist[$index]->$tag;
     
        return $value;
     }
     
    /**
     * This function gets Catalog id and tag name, then returns a value
     * @param catalogid
     * @param tagname
     * @return value 
     **/ 
     public function getValByCatalogIdAndTag($id,$tag){
        
        $index = $this->getCatalogIndex($this->catalogs,$id);   
        $value = $this->catalogs[$index]->$tag;
     
        return $value;
     }
      
     /**
     * This function gets Provship id and tag name, then returns a value
     * @param provshipid
     * @param tagname
     * @return value 
     **/ 
     public function getValByProvshipIdAndTag($id,$tag){
        
        $index = $this->getShippingTypeIndex($this->shipTypes,$id);   
        $value = $this->shipTypes[$index]->$tag;
        return $value;
     }
     
    /**
     * This function gets area id and tag name, then returns a value
     * @param areaid
     * @param tagname
     * @return value 
     **/ 
     public function getValByAreaIdAndTag($id,$tag){
        
        $index = $this->getRateslistshippingareasIndex($this->areas,$id);   
        $value = $this->areas[$index]->$tag;
        return $value;
     }
     
     /**
      * This function gets provship id and catalog id, then returns area id's array
      * @param provshipid
      * @param catalogid
      * @return arealist
      **/ 
      public function getAreaByProvshipAndCatalog($provshipId,$catalogId){
        
        $count = 0;
        $areas = array();
        
        for($i=0;$i<count($this->catalogs);$i++){
            if($this->catalogs[$i]->ItemID == $catalogId)
               $catalog = $this->catalogs[$i];
        }
        for($i=0;$i<count($catalog->shipping_methods->ShippingType);$i++){
            if($catalog->shipping_methods->ShippingType[$i]->provshipid == $provshipId){
                $shiptype = $catalog->shipping_methods->ShippingType[$i];
                }
        }
        for($i=0;$i<count($shiptype->provship_cost->Rateslistshippingareas);$i++){
            $areas[$count++] = (string)$shiptype->provship_cost->Rateslistshippingareas[$i]->areaid;
        }
        if(count($areas)>0) return $areas;
        else return 0;
        
      }
      
  /**
   * This function gets an area id and returns the cost for this area
   * @param areaid
   * @return cost
   **/ 
  public function getCostByAreaId($areaid){
    
    for($i=0;$i<count($this->areas);$i++){
        if($this->areas[$i]->areaid == $areaid){
            return $this->areas[$i]->cost;
        }   
    }
    return;
   }
       
  /**
   * This function Create XML by location  
   * @param countryid
   * @param cityid
   * @param sporttypeid
   * @return xml
   **/ 
   public function SearchByLocation($countryid = 0,$cityid = 0,$sporttypeid = 0){
    
      $url = "http://widgets.sportsevents365.com/data/tickets/v2.0/events/?q=lq,$countryid,$cityid,$sporttypeid";
      $this->url = $url;
      $str = file_get_contents($url , false ,$this->context);
      $this->xml = simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA);
      $this->init();
      /*
      echo "<br />$str<br />";
      if(isset($this->xml))
        echo "<br /><br />Its set<br /><br />";
      else 
        echo "<br /><br />Its Unset<br /><br />";
      */          
   }
       
  /**
   * This function Search the results by Competitior
   * @param competitorid
   * @return xml
   **/ 
    public function SearchByCompetitor($competitorid = 0){
    
      $url = "http://widgets.sportsevents365.com/data/tickets/v2.0/events/?q=cq,$competitorid";
      $this->url = $url;
      $str = file_get_contents($url , false ,$this->context);
      $this->xml = simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA);
      $this->init();            

   }
       
   /**
   * This function Search the results by Sport type
   * @param sporttypeid
   * @return xml
   **/ 
   public function SearchBySport($sporttypeid = 0){
    
      $url = "http://widgets.sportsevents365.com/data/tickets/v2.0/events/?q=cq,0,$sporttypeid";
      $this->url = $url;
      $str = file_get_contents($url , false ,$this->context);
      $this->xml = simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA);
      $this->init();
        
   }
   /**
   * This function Search the results by tournament
   * @param tournamentid
   * @return xml
   **/ 
   public function SearchByTournament($tournamentid = 0){
    
      $url = "http://widgets.sportsevents365.com/data/tickets/v2.0/events/?q=tq,$tournamentid,1000&perpage=100&cur=USD";
      $this->url = $url;
      $str = file_get_contents($url , false ,$this->context);
      $this->xml = simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA);
      $this->init();      
   }
       
   /**
   * This function Search the results by Subframe
   * @param sporttypeid
   * @return xml
   **/ 
    public function SearchBySubframe($subframeid = 0){
        
      $url = "http://widgets.sportsevents365.com/data/tickets/v2.0/events/?q=sq,$subframeid";
      $this->url = $url;
      $str = file_get_contents($url , false ,$this->context);
      $this->xml = simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA);  
      $this->init();      
   }  
     
      
       
  /**
   * This function Search the results by a Query string
   * @param querystring
   * @return xml
   **/ 
    private function SearchByQueryString($querystring){
        
      $url = $querystring;
      $this->url = $url;
      $str = file_get_contents($url , false ,$this->context);
      $this->xml = simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA);                     
      $this->init();      
   }
       
   /**
    * This function gets catalog id and returns it price
    * @param catalogid
    * @return price
    **/ 
    public function getPricebycatalogid($catalogid){
        
        $price = -1;
        //$this->eventlist = $this->getevents();
        //$this->createCatalogArray($this->eventlist);
        
        for($i=0;$i<count($this->catalogs);$i++){
            if($this->catalogs[$i]->ItemID==$catalogid){
               $price = $this->catalogs[$i]->Price;
               return $price;
            }
        }
        return $price;
    }
        
    /**
     *  This function gets an XML Object, checks witch page is the current one,
     *  Then returns the next page of the XML Object
     *  @param XML
     *  @return XML(next page) 
     **/ 
     public function goToNextPage(){
        
        
        $page = (string)$this->xml->control->page;
        $total = (string)$this->xml->control->totalpages;
        if($page == $total){
           echo "<br />You are in the last page";
           return;
           }
        $nextPage = ($page+1);
        $this->url.="&page=$nextPage";
        $this->SearchByQueryString($this->url);
        
     }
         
    /**
     *  This function checks witch page is the current one in this->xml,
     *  Then Search again with the next page URL and change this->xml to the requested page 
     *  @param XML
     *  @return XML(next page) 
     **/ 
     public function goToPreviusPage(){
        
        $page = (string)$this->xml->control->page;
        if($page == 1){
           echo "<br />You are in the first page";
           return;
           }
        $previusPage = ($page-1);
        $this->url.="&page=$previusPage";
        $this->SearchByQueryString($this->url);
        
     }
         
     /**
      * This function gets number of events per page and create a url and xml 
      * according to this number
      **/  
      public function perPage($perpage){
        
        $this->url.= "&perpage=$perpage";
        $this->SearchByQueryString($this->url);
      }
          
      /**
       * This function gets a page number and change this->xml according to
       * the requested page 
       **/ 
       public function goTopageNumber($pagenum){
        
        $firstPage = 1;
        $lastPage = $this->xml->control->totalpages;
        
        if($pagenum < $firstPage || $pagenum > $lastPage){
            echo "<br />You choose inccorect value";
            return;
        }
        $this->url.="&page=$pagenum";
        $this->SearchByQueryString($this->url);
        
     } 
 
}

?>