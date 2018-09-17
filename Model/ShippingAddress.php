<?php 

namespace Rayms\OrderEventsBroadcaster\Model;

use Rayms\OrderEventsBroadcaster\Model\AbstractAddress;

class ShippingAddress extends AbstractAddress 
{

    private $address;

    public function __construct($address) 
    {

        $this->address = $address;
    }

   public function getStreet() : string 
   {
        
        if (!empty($this->address->getStreet())) {

            return $this->address->getStreet()[0]. ' '. $this->address->getStreet()[1];
        }
        
        return "n/a";
   }

   public function getCity() : string 
   {
        
        if (!empty($this->address->getCity())) {

            return $this->address->getCity();
        }
        
        return "n/a";
    }

    public function getRegion() : string 
    {
        
        if (!empty($this->address->getRegion())) {

            return $this->address->getRegion();
        }
        
        return "n/a";
    }

    public function getCountry() : string 
    {
        
        if (!empty($this->address->getCountryId())) {

            return $this->address->getCountryId();
        }
        
        return "n/a";
    }
    
    public function getShipperFullName() : string 
    {
        
        if (!empty($this->address->getFirstname()) && !empty($this->address->getLastname()) ) {

            return $this->address->getFirstname(). ' '. $this->address->getLastname();
        }
        
        return "n/a";
    }

   public function getFullAddress()
   {
       return 
            [
                'street'          =>  $this->getStreet(),
                'city'            =>  $this->getCity(),
                'region'          =>  $this->getRegion(),
                'country'         =>  $this->getCountry(),
                'shipper_name'    =>  $this->getShipperFullName()
            ];
    }
}