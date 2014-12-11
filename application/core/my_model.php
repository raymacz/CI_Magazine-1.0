<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of my_model
 *
 * @author owner
 */
class MY_model extends CI_Model {
    //put your code here
    const DB_TABLE = 'abstract';
    const DB_TABLE_PK = 'abstract';
    
    /*
     * Create record.
     */
    
    private function insert() {
       /// $xxx=$this->db->insert_id();
       ///$this->{$this::DB_TABLE_PK}=0;
        $this->db->insert($this::DB_TABLE,  $this);
        $this->{$this::DB_TABLE_PK} = $this->db->insert_id();      //$this->db->insert($table, $set)
        //had lots of  trouble solving this, the  problem was the database auto increment & not null not  CI
        $pause=0;
        //UNFINISHED
    }
    
    private function update() {
        $this->db->update($this::DB_TABLE, $this, $this::DB_TABLE_PK);       //??? question maybe $this->$this::DB_TABLE_PK
        $pause=0;
        // $this->db->update($table, $set, $where);
        
    }
        
    /*
     * Populate from an array or standard class
     * @param mixed $row
     */
    public function populate($row)  {
        foreach ($row as $key  => $value) {
            $this->$key=$value; //object-> populate properties with values $x="user";  $$x="value";   echo $user; // value
        }
    }
    /*
     * @param int $id. load from database
     */
    
    public function load ($id) {
        $query = $this->db->get_where($this::DB_TABLE,  array($this::DB_TABLE_PK => $id,)); //where needs to be an array()
      //  $this->db->get_where($table, $where) 
      $this->populate($query->row());// row() is codeigniter method
      ///  $this->populate($this); 
       $pause=0;
        
    }
    
    public function delete() {
        $this->db->delete($this::DB_TABLE, array($this::DB_TABLE_PK => $this->{$this::DB_TABLE_PK},)); //maybe abstract
        //$this->db->delete($table, $where)
        unset($this->{$this::DB_TABLE_PK});
    }
    
    public function save() {
        if (isset($this->{$this::DB_TABLE_PK})) {
            $this->update();
        } else {
            $this->insert();
        }
        $pause=0;
    }
    
    /*
     * get an array of models with an optional limit, offset.
     * 
     * @param int $limit Optional.
     * @param int $offfset Optional. if set,  requires $limit.
     * @return array of Models populated  by database,  keyed  by  PK.
     */
    
    public function get($limit = 0, $offset = 0) {       
        if ($limit) {
            $query = $this->db->get($this::DB_TABLE, $limit, $offset);   //compiles & runs the query & returns object        
           // $this->db->get($table, $limit, $offset);
        } else {
            $query = $this->db->get($this::DB_TABLE); 
        }   
            $ret_val  = array();
            $class = get_class($this);          //  get_class($object);
        ////       $xxx_array = array(); ///
          ///  $xxx_array = $query->result();/// gets object & assign to array
            foreach ($query->result() as $row) {//array ?[0]->['populaton_id'] and ?[0]->['populaton_name']
                $model = new $class;
                $model->populate($row);//object $row->population_id and $row->population_name
                // array of models //array $ret_val[0]->$Publication->population_id and $ret_val[1]->$Publication->population_name;
                $ret_val[$row->{$this::DB_TABLE_PK}] = $model; 
              ///  $yy=$ret_val[6]->publication_id; ///
              ///  $xx=$ret_val[$row->{$this::DB_TABLE_PK}]->publication_id;///
                //just how to get value of array index with (object name) object properties
                $pause=0;
            }
            return $ret_val;
    }
    
 }  
 
 