<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of issues
 *
 * @author owner
 */
class Issue extends  MY_model { //2nd gen for CI_Model // upto 3rd gen?
    const DB_TABLE = 'issues';
    const DB_TABLE_PK = 'issue_id';
    //put your code here
    /*
     * issue_id @var int unique indentifier
     */
    public $issue_id; 
    /*
     * publication_id $var int  publication unifiying record
     */
    public $publication_id;
    /*
     * publisher assigned issue number
     * @var int
     */
     public $issue_number;
     
     /*
      * @var string Date that the issue was published 
      */
     
     public $issue_date_publication;      /*
          * 
      * path to the file containing the cover image
      * @var string
          */
    public $issue_cover;
   
}
