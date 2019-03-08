<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Content extends CI_Controller {

	var $MODEL = 'Mdl_content';
	var $TITLE = 'Content';

	function Content() {
		error_reporting(E_ALL);
		parent::__construct();
		// $this->Mdl_common->check_session();
		$this->load->model($this->MODEL, 'model');
        // $this->load->library('Ajax_pagination');
        // $this->perPage = 3;
	}

	function index() 
	{
		$this->load->view('content');
	}

	function companydata()
	{
		$res=$this->model->getCarsByUserId();
		$data['carsList'] = array_slice($res, 0, 3);
		$data['carsCount']=count($res);
		$this->load->view('content/companydata',$data);
	}

	//load more
	function getData()
	{
		$data['carsList'] = $this->model->getLimitedData();
		$data['carsCount']= count($data['carsList']);
		
		$output=$this->load->view('ajaxTemplate/moreCars',$data,true);
		echo json_encode($output);
	}

	function setting()
	{
		$this->load->view('content');
	}

	function add()
	{
		$this->load->view('content/addCar');   
	}

	function update($id)
	{
		if($this->model->verifyCar($id) == "true")
		{
			$data['carInfo'] = $this->model->getCarById($id);
			$data['carImages'] = $this->model->getCarImages($id);
			$data['primaryImage']= $this->model->getPrimaryImage($id);
			$this->load->view('content/addCar',$data);
		}
		else
		{
			redirect();
		}
	}

	function save()
	{
		$res['response']=$this->model->saveCar();
		echo json_encode($res);
	}

	function addTempLogo()
	{
		$src = $_FILES['carL']['tmp_name'];
		$res=$this->Mdl_common->uploadFile('carL','img','cars',time().$_FILES['carL']['name']);
		$this->session->set_userdata('carLogo',$res['path']);
	}

	function addTempGallery()
	{
		if (isset($_FILES['files']) && !empty($_FILES['files'])) {
			$no_files = count($_FILES["files"]['name']);
			for ($i = 0; $i < $no_files; $i++) {
				if ($_FILES["files"]["error"][$i] > 0) {
					echo "Error: " . $_FILES["files"]["error"][$i] . "<br>";
				} else {
					$fileName = time() . $_FILES["files"]["name"][$i];
					$count=count($this->session->carGalleryImages);
					$_SESSION['carGalleryImages'][$count+1] = $fileName;
					move_uploaded_file($_FILES["files"]["tmp_name"][$i], 'uploads/cars/' . $fileName);
				}
			}
		}
		// pr($this->session->carGalleryImages);
	}

	function removeImageFromSession()
	{
		$name=$this->input->post('imgName');
		$sessionImgs=$this->session->carGalleryImages;
		$index = array_search($name,$sessionImgs);
		if($index !== FALSE){
			unset($sessionImgs[$index]);
		}
		session_unset('carGalleryImages');
		$this->session->set_userdata('carGalleryImages',$sessionImgs);
		$data['msg']="success";
		echo json_encode($data);
	}

	function us()
	{
		session_unset('carLogo');
		session_unset('carGalleryImages');
	}

	function deleteImageOfCar()
	{
		$res=$this->model->deleteImageOfCar();
		if($res == "deleted")
		{
			$data['response']="deleted";
			echo json_encode($data);
		}
	}

	function deletecar()
	{
		$id=$this->input->post('carId');
		if($this->model->verifyCar($id))
		{
			$res=$this->model->deleteCar($id);
			if($res == "deleted")
			{
				$data['response']="deleted";
				echo json_encode($data);
			}
		}
		else
		{
			redirect();
		}
	}
	function detail($id)
	{
		$data['carInfo'] = $this->model->getCarById($id);
		$data['carImages'] = $this->model->getCarImages($id);
		$data['primaryImage']= $this->model->getPrimaryImage($id);
		$this->load->view('content/details',$data);
	}

	function getVINDetails()
	{
		$apiPrefix = "https://api.vindecoder.eu/2.0";
		$apikey=VINAPIKEY;
		$apiSecret= VINAPISECRET;
		$vin = $this->input->post('vinNumber');
		$controlsum = substr(sha1("{$vin}|{$apikey}|{$apiSecret}"), 0, 10);
		$data = file_get_contents("{$apiPrefix}/{$apikey}/{$controlsum}/decode/{$vin}.json", false);
		// $result = json_decode($data);

		echo $data;
	}

	function listing()
	{
		$res=$this->model->getCarsByUserId();
		$data['carList']=$res;
		$data['totalCarCount']= count($res);
		// pr($data);die;
		$this->load->view('content/listing',$data);
	}

	function addcar()
	{
		$this->load->view('content/addcar_new');
	}

	function edit($id)
	{
		if($this->model->verifyCar($id) == "true")
		{
			$data['carInfo'] = $this->model->getCarById($id);
			$data['carImages'] = $this->model->getCarImages($id);
			$data['primaryImage']= $this->model->getPrimaryImage($id);
			// pr($data);die;
			$this->load->view('content/addcar_new',$data);
		}
		else
		{
			redirect();
		}
	}

	function documentStorage()
	{
		$res=$this->model->getCarsByUserId();
		$data['carList']=$res;
		// pr($data);die;
		$this->load->view('crm/listing',$data);
	}

	function generatePdf($id)
	{	
		$data['carDetails']=$this->model->getCarById($id);
		$data['PrimaryImage']=$this->model->getPrimaryImage($id);

	}

	function addcarTab()
	{
		$this->load->view('content/addcarTab');
	}
}

?>
