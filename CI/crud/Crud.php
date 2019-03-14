<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Crud
 */
class Crud extends CI_Controller {

    var $MODEL = 'Mdl_crud';
    var $TITLE = 'CI CRUD';

    /**
     * Crud constructor.
     */
    function __construct() {
        parent::__construct();
        error_reporting(E_ALL);
        $this->load->model($this->MODEL,'model');
    }

    /**
     *
     */
	public function index()
	{
	    $data['gridData'] = $this->model->getAllData();
//	    echo "<pre>";print_r($data);die;
		$this->load->view('crud/index',$data);
	}

    /**
     * redirecting to add form
     */
	public function manage($id=null)
    {
        if($id==null){
            $this->TITLE="Add";
            $this->load->view('crud/add');
        }
        else{
            $this->TITLE="Update";
            $data['userData']=$this->model->getUserData($id);
            $data['userImages']=$this->model->getUserImages($id);
            $this->load->view('crud/add',$data);
        }
    }

    public function addTempImages(){
        if (isset($_FILES['files']) && !empty($_FILES['files'])) {
            $no_files = count($_FILES["files"]['name']);
            for ($i = 0; $i < $no_files; $i++) {
                if ($_FILES["files"]["error"][$i] > 0) {
                    echo "Error: " . $_FILES["files"]["error"][$i] . "<br>";
                } else {
                    $fileName = time() . $_FILES["files"]["name"][$i];
                    $count=count($this->session->sessImages);
                    $_SESSION['sessImages'][$count+1] = $fileName;
                    move_uploaded_file($_FILES["files"]["tmp_name"][$i], 'images/uploads/' . $fileName);
                }
            }
        }
//        print_r($this->session->sessImages);
    }

    public function save(){
	    $this->model->save();
	    redirect('crud');
    }

}
