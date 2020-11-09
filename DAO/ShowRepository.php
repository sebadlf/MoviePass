<?php

namespace DAO;

use Models\Show as Show;
use DAO\Connection as Connection;
use \Exception as Exception;

class ShowRepository {

    private $connection;
    private $tableName = " SHOWS ";

    public function __construct()
    {
        $this->connection = null;
    }

    
    function GetAll()
    {
        try
        {
            $ret = array();
            $query = "SELECT * FROM " . $this->tableName . " WHERE ACTIVE = 1";
            $this->connection = Connection::GetInstance();
            $queryResult = $this->connection->Execute($query);
            
            $ret = Show::mapData($queryResult);

            return $ret;
        }catch(Exception $ex){
            throw $ex;
        }
    }

    function GetById($id)
    {
        try
        {
            $ret = array();
            $query = "SELECT * FROM " . $this->tableName . " WHERE ID = :id ;";
            $parameters['id'] = $id;
            $this->connection = Connection::GetInstance();
            $queryResult = $this->connection->Execute($query, $parameters);

            $ret = Show::mapData($queryResult);

            return $ret[0];
        }catch(Exception $ex){
            throw $ex;
        }
    }

    public function AddOne($show){
        try
        {
            $query = 'INSERT INTO ' . $this->tableName . ' (MOVIE_ID, ROOM_ID, DATETIME_FROM, DATETIME_TO) VALUES (:movieId, :roomId, :dateTimeFrom, :dateTimeTo);';
            
            $parameters['movieId'] = $show->getMovieId();
            $parameters['roomId'] = $show->getRoomId();
            $parameters['dateTimeFrom'] = $show->getDateTimeFrom();
            $parameters['dateTimeTo'] = $show->getDateTimeTo();

            $this->connection = Connection::GetInstance();
            
            $this->connection->ExecuteNonQuery($query, $parameters);
            return "Función Agregada correctamente";
        }catch(Exception $ex){
            return "Ha ocurrido un error :( " . $ex->getMessage();
        }
    }

    public function DeleteShow($id){

        $sql = "UPDATE " . $this->tableName . " SET ACTIVE = 0 WHERE ID = :ID";
        $parameters['ID'] = $id;

        try
        {
            $this->connection = Connection::getInstance();
            $this->connection->ExecuteNonQuery($sql, $parameters);
            return "Eliminado Correctamente.";
        }
        catch(Exception $e)
        {
            return "Ha ocurrido un error :( " . $e->getMessage();
        }
    }

    public function Update($show){
        
        try
        {
            $sql = "UPDATE " . $this->tableName . " SET MOVIE_ID = :movieId, ROOM_ID = :roomId, DATETIME_FROM = :dateTimeFrom, DATETIME_TO = :dateTimeTo WHERE ID = :Id";
            
            $parameters['Id'] = $show->getId();
            $parameters['movieId'] = $show->getMovieId();
            $parameters['roomId'] = $show->getRoomId();
            $parameters['dateTimeFrom'] = $show->getDateTimeFrom();
            $parameters['dateTimeTo'] = $show->getDateTimeTo();
            
            $this->connection = Connection::getInstance();
            $this->connection->ExecuteNonQuery($sql, $parameters);

            return "Registro modificado correctamente";
        }
        catch(Exception $e)
        {
            return "Ha ocurrido un error :( " . $e->getMessage();
        }
    }

    public function GetByDateAndMovieId($dateFrom, $movieId){
        try
        {
            $query = "SELECT * FROM " . $this->tableName . " WHERE DATE(DATETIME_FROM) = :showDate AND MOVIE_ID = :movieId;";
             
            $parameters['showDate'] = date('Y-m-d', strtotime($dateFrom));
            $parameters['movieId'] = $movieId;
            
            $this->connection = Connection::getInstance();
            $queryResult = $this->connection->Execute($query, $parameters);

            $ret = Show::mapData($queryResult);
            
            return $ret;
        }
        catch(Exception $e)
        {
            return "Ha ocurrido un error :( " . $e->getMessage();
        }
    }
    
    public function GetByRoomIdAndMovieId($roomId, $movieId){
        try
        {
            $query = "SELECT SH.* FROM SHOWS SH
                    INNER JOIN ROOM ON ROOM.ID = SH.ROOM_ID
                    INNER JOIN CINEMA CIN ON CIN.ID = ROOM.cineId
                    WHERE MOVIE_ID = :movieId 
                    AND CIN.ID = (select cineId from room where id = :roomId)";

            $parameters['roomId'] = $roomId;
            $parameters['movieId'] = $movieId;
            
            $this->connection = Connection::getInstance();
            $queryResult = $this->connection->Execute($query, $parameters);

            $ret = Show::mapData($queryResult);
            
            return $ret;
        }
        catch(Exception $e)
        {
            return "Ha ocurrido un error :( " . $e->getMessage();
        }
    }
    
    
    public function GetShowByDatePlusMinutes($dateFrom, $minutes){
        
        $query = "SELECT * FROM " . $this->tableName . " WHERE DATETIME_FROM > date_add( :dateFrom , interval " . -$minutes . " minute) AND DATETIME_FROM <= :anotherDateFrom ;";
        
        $parameters['dateFrom'] = date('Y-m-d h:m:s', strtotime($dateFrom));
        $parameters['anotherDateFrom'] = date('Y-m-d h:m:s', strtotime($dateFrom));
        
        $this->connection = Connection::getInstance();
        $queryResult = $this->connection->Execute($query, $parameters);
        
        $ret = Show::mapData($queryResult);
            
        return $ret;
    }

    public function GetShowIsPlayingInDateTime($dateFrom){
        $query = "SELECT * FROM " . $this->tableName . " WHERE :dateFrom BETWEEN DATETIME_FROM and DATETIME_TO;";
        
        $parameters['dateFrom'] = date('Y-m-d h:m:s', strtotime($dateFrom));
        
        $this->connection = Connection::getInstance();
        $queryResult = $this->connection->Execute($query, $parameters);
        
        $ret = Show::mapData($queryResult);
            
        return $ret;
    }

}

?>