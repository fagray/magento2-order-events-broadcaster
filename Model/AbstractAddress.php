<?php 

namespace Rayms\OrderEventsBroadcaster\Model;

abstract class AbstractAddress 
{

    abstract protected function getStreet();
    abstract protected function getCity();
    abstract protected function getRegion();
    abstract protected function getCountry();
    abstract protected function getFullAddress();
}