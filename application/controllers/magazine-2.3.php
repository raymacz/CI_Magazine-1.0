<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 if ( !defined('BASEPATH')) exit('No direct script  access allowed');

 class Magazine extends CI_Controller {
     /**
      * Index page for Magazine controller
      * //go to routes to change the url magazine to index
      */
     public function index() {
         $this->load->model('Publication'); 
         $this->Publication->publication_name = "Sandy Shore";
        /// $this->Publication->publication_id = 1;  //tempo //??? this has been because lynda is a TOTAL FAILURE!  //CodeIgniter User Guide Version 2.2.0  maybe it works
         //had lots of  trouble solving this, the  problem was the database structure auto increment & not null not  CI
         $this->Publication->save();
         echo '<tt><pre>'.  var_export($this->Publication, TRUE).'</tt></pre>';  //var_export($expression, $return)
         //debug
         $this->load->model('Issue');
         $issue = new Issue();
         $issue->publication_id= $this->Publication->publication_id;
        // $issue->issue_id = 1; //tempo //??? this has been because lynda is a TOTAL FAILURE! 
         //had lots of  trouble solving this, the  problem was the database structure auto increment & not null not  CI
         $issue->issue_number = 5;
         $issue->issue_date_publication = date('2013-02-01'); //CI will take care of date format
         $issue->save();
         echo '<tt><pre>'.  var_export($issue, TRUE).'</tt></pre>';  //var_export($expression, $return) 
         //debug
         echo '<tt><pre>'. 'VAR_DUMP: '.var_dump($issue).'</tt></pre>';  //var_export($expression, $return) 
         //debug
         $this->load->view('magazines');
         $pause=0;
     }
 }