<?php

namespace Models;

class Cinema{

    private $id;
    private $name;
    private $address;
    private $cityId;
    private $active;
    
    //Not Mapped
    private $cityDescription;
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }


    public function getCityId()
    {
        return $this->cityId;
    }

    public function setCityId($cityId)
    {
        $this->cityId = $cityId;

        return $this;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    public static function mapData($value) {

        $value = is_array($value) ? $value : [];

        
        $resp = array_map(function($p){
            $cinema = new Cinema();
            $cinema->setId($p['ID']);
            $cinema->setName($p['NAME']);
            $cinema->setAddress($p['ADDRESS']);
            $cinema->setCityId($p['CITY_ID']);
            $cinema->setActive($p['ACTIVE']);
            return $cinema;
        }, $value);

        if($resp != null){
            //return count($resp) > 1 ? $resp : $resp[0];
            return $resp;
        }
        else
            return array();
    }

    public function getCityDescription()
    {
        return $this->cityDescription;
    }

    public function setCityDescription($cityDescription)
    {
        $this->cityDescription = $cityDescription;

        return $this;
    }
}

?>