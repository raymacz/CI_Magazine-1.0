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
         $this->load->helper('url');
         $this->load->view('bootstrap/header');
         $this->load->library('table');
         $magazines=array();
         //loads Publication & Issue Object database
         $this->load->model( array(
               'Issue',
               'Publication',
         )); 
         //get records by query  &
        $Issues= $this->Issue->get();
         ///$magazines[] = array_merge($Issues, $Publications); ///
         //all Issues objects, load Publication objects using publication_id to get publication_name
         foreach ($Issues as $Issue) :
             $publication = new Publication();
             $publication->load($Issue->publication_id);
             $magazines[]=array(
               $publication->publication_name,
                $Issue->issue_number,
                !$Issue->issue_date_publication ? 'Date Unspecified' : $Issue->issue_date_publication , ///test
                $Issue->issue_cover ? ' Yes' : ' No', // if issue_cover has filename print YES
               anchor('magazine/view/'.  $Issue->issue_id, 'View') .' | '.
               anchor('magazine/delete/'. $Issue->issue_id, 'Delete'),  
               );              // anchor($uri, $title, $attributes)
        endforeach;
        //load all magazines object to view     
               $this->load->view('magazines', array(
                 'magazines' => $magazines,
              ));
     
//         $data = array();
//        $this->load->model('Publication');
//        $publication = new Publication();
//        $publication->load(6);  // load($id) - table id // found in MY_model
//        $data['publication'] = $publication;
//        
//        $this->load->model('Issue');         // model($model, $name, $db_conn) //$model  name  of the model class 
//        $issue = new Issue();
//        $issue->load(5);
//        $data['issue'] = $issue;
//        $this->load->view('magazines', $data); //loads object and displays it on magazines.php in view
//        $this->load->view('magazine', $data); //$this->load->view($view, $vars, $return) $view - file $data - assoc. array
//        
         $this->load->view('bootstrap/footer');
         $pause=0;
     }
     
     public function add() {
        $config = array(
          'upload_path' => 'upload',
          'allowed_types' => 'gif|jpg|png',
          'max_size' => 250,
          'max_width' => 1920,
          'max_height' => 1080,
        );
        $this->load->library('upload', $config);
        $this->load->helper('form');
         /***
          * move js,css,image, then adjust the html href, & <link>, & <script>, etc. path in bootstrap 
          * project URL: http://localhost/CI_proj1/CI_lynda/ 
          * copy Sources file to another location
          */
        $this->load->view('bootstrap/header');
        $this->load->model('Publication');
        $publications = $this->Publication->get();        
        $publication_form_options = array();
        foreach ($publications as $id => $publication) { //foreach on array  index => object
            $publication_form_options[$id] = $publication->publication_name;
        }
        $this->load->library('form_validation');
       //$this->load->library($library, $params, $object_name);
       $this->form_validation->set_rules(array(
         array(
           'field'=>'publication_id',
           'label'=>'Publication',
           'rules'=>'required',
           ),
        array(
            'field' => 'issue_number',
            'label' => 'Issue number',
            'rules' => 'required|is_numeric',
          ),
         array(
            'field' => 'issue_date_publication',
            'label' => 'Publication date',
            'rules' => 'required|callback_date_validation', 
                ///start with a callback_ followed by the actual name of the method date_validation
         ),
       ));
        // $this->form_validation->set_rules($field, $label, $rules);
       $this->form_validation->set_error_delimiters('<div class="alert alert-error">', '</div>');
      // $this->form_validation->set_error_delimiters($prefix, $suffix);
       $check_file_upload=FALSE;
       if (isset($_FILES['issue_cover']['error']) && $_FILES['issue_cover']['error'] != 4) {
           $check_file_upload = TRUE;
       }
     // $this->form_validation->run($group); // $this->upload->do_upload($field); 
       if (!$this->form_validation->run() || ($check_file_upload && !$this->upload->do_upload('issue_cover'))) { 
       //if validation has already run then get post input
              $this->load->view('magazine_form', array(
                'publication_form_options' => $publication_form_options, 
            ));
        }   
        else { ///get post input
            $this->load->model('Issue');
            $issue= new Issue();
            //transfer post to object
            $issue->publication_id = $this->input->post('publication_id'); //$this->input->post($index, $xss_clean);
            $issue->issue_number = $this->input->post('issue_number'); 
            $issue->issue_date_publication  = $this->input->post('issue_date_publication');
            //get upload data & assign to object
            $upload_data = $this->upload->data();
            if (isset($upload_data['file_name'])) {
                $issue->issue_cover = $upload_data['file_name'];
            }
            $issue->save();
            $this->load->view('magazine_form_success', array(
              'issue' => $issue, //pass the issue to view to  show $issue_id
            ));
        }
        $this->load->view('bootstrap/footer');
        $pause=0;
    }
     
    /**
     * Date validation callback.
     * @param string $input
     * @return boolean
     */
    public function date_validation($input) {
        $test_date = explode('-', $input);
        if (!@checkdate($test_date[1], $test_date[2], $test_date[0])) {
            $this->form_validation->set_message('date_validation', 'The %s field must be in YYYY-MM-DD format.');
            return FALSE;
        }
        return TRUE;
    }
     
    /* View a magazine.
     * @param type $issue_id
     */
    public function view($issue_id) {
        $this->load->helper('html');
        $this->load->view('bootstrap/header');
        $this->load->model(array('Issue','Publication'));
        $issue = new Issue();
        $issue->load($issue_id);
        if (!$issue->issue_id) {
            show_404();//php function
        }
        $publication = new Publication();
        $publication->load($issue->publication_id);
        $this->load->view('magazine',array(
            'issue' => $issue,
            'publication' => $publication,
        ));
         $this->load->view('bootstrap/footer');  

    }
    /*
     * Delete a magazine.
     * @param type int $issue_id
     */
    
    public function delete($issue_id) {
        $this->load->view('bootstrap/header');
        $this->load->model(array('Issue'));
        $issue = new Issue();
        $issue->load($issue_id);
        if (!$issue->issue_id) {
            show_404();//php function
        }
        $issue->delete();
        $this->load->view('magazine_deleted', array(
          'issue_id' => $issue_id,
        ));      
        $this->load->view('bootstrap/footer');
    }
 }
 
// UNFINISHED NEXT 5 lynda