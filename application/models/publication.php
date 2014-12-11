<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of publication
 *
 * @author owner
 */
//class Publication extends CI_Model {
class Publication extends MY_model { //2nd gen for CI_Model // upto 3rd gen?
    const DB_TABLE = 'publications';
    const DB_TABLE_PK = 'publication_id';
    //put your code here
    /*
     * $param -  int  unique identifier
     */
    public $publication_id;
        /*
     * $param  srting
     */
    public $publication_name;
    
}
