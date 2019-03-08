<?php

class Mdl_content extends CI_Model {

	function getCarsByUserId()
	{
		// $userId= $this->session->dashboard['dashUserId'];
		return $this->db->select('aa_cars.*,aa_users.username')->from('aa_cars')->join('aa_users','aa_cars.user_id=aa_users.user_id')->where('aa_users.user_id',1)->where('aa_cars.del_in',0)->order_by('car_id','desc')->get()->result_array();
	}

	//load more
	function getLimitedData()
	{
    	// $userId= $this->session->userId;
		$start= $this->input->post('start');
		$userId= $this->session->dashboard['dashUserId'];
		$str="select * from aa_cars where del_in = '0' and user_id= '$userId' order by car_id desc limit $start, 3";
		return $res = $this->db->query($str)->result_array();
	}

	function getCarById($id)
	{
		return $this->db->where('car_id',$id)->get('aa_cars')->row();
	}

	function getCarImages($id)
	{
		return $this->db->where('car_id',$id)->where_not_in('is_primary',1)->get('aa_cars_image')->result_array();
	}

	function getPrimaryImage($id)
	{
		return $this->db->where('car_id',$id)->where('is_primary',1)->get('aa_cars_image')->row();
	}

	function saveCar()
	{
		$userId= 1;
		// $userId= $this->session->dashboard['dashUserId'];
		$longLatArr=array(
			'lat' =>$this->input->post('lat'),
			'long' => $this->input->post('lng'),
			'location'=> $this->input->post('carLocation')
		);
		$longLatEncoded= json_encode($longLatArr);
		$data= array(
			'user_id'=>$userId,
			'template' => '1',
			'title'=>$this->input->post('carTitle'),
			// 'tagline'=>$this->input->post('carTagline'),
			'description'=>$this->input->post('carDesc'),
			'email'=>$this->input->post('carEmail'),
			'phone'=>$this->input->post('carPhone'),
			// 'country'=>$this->input->post('carCountry'),
			'state'=>$this->input->post('carState'),
			'city'=>$this->input->post('carCity'),
			'zip'=>$this->input->post('carZip'),
			'location'=>$longLatEncoded,
			'vin_number'=>$this->input->post('carVinNUmber'),
			'category'=>$this->input->post('carCategory'),
			'brand'=>$this->input->post('carBrand'),
			'series'=>$this->input->post('carSeries'),
			'color'=>$this->input->post('carColor'),
			'interior_color'=>$this->input->post('carInteriorColor'),
			'manufacturing_year'=>$this->input->post('carManufacturingYear'),
			'fuel'=>$this->input->post('carFuel'),
			'transmission'=>$this->input->post('carTransmission'),
			'drivetrains'=>$this->input->post('carDrivetrains'),
			'status'=>$this->input->post('carStatus'),
			'engine'=>$this->input->post('carEngine'),
			'mileage'=>$this->input->post('carMileage'),
			'displacement'=>$this->input->post('carDisplacement'),
			'weight'=>$this->input->post('carWeight'),
			'fuel_usage'=>$this->input->post('carFuelUsage'),
			'no_of_doors'=>$this->input->post('carNoOfDoors'),
			'max_passengers'=>$this->input->post('carMaxPassengers'),
			'tags'=> json_encode($this->input->post('tag')),
			'price'=>$this->input->post('carPrice'),
		);

		$hid= $this->input->post('hiddenCarId');
		if($hid != "")
		{
			$this->db->where('car_id',$hid)->update('aa_cars',$data);
			$primaryImage = $this->session->carLogo;
			if(isset($primaryImage))
			{
				$this->db->where('car_id',$hid)->where('is_primary',1)->delete('aa_cars_image');
				$imgData=array(
					'car_id' => $hid,
					'image_name'=>$this->session->carLogo,
					'is_primary'=> '1'
				);
				$this->db->insert('aa_cars_image',$imgData);
			}

			$imageArr=$this->session->carGalleryImages;
			if(isset($imageArr))
			{
				foreach ($imageArr as $row) {
					$imgData=array(
						'car_id' => $hid,
						'image_name'=>"uploads/cars/".$row,
					);
					$this->db->insert('aa_cars_image',$imgData);
				}
			}
			$this->session->unset_userdata('carLogo');
			$this->session->unset_userdata('carGalleryImages');
			return "updated";
		}
		else
		{
			$data['created_on']= date("Y-m-d H:i:s");
			$this->db->insert('aa_cars',$data);	
			if ($this->db->affected_rows() > 0) {
				$insertedId = $this->db->insert_id();
			} 

			$primaryImage = $this->session->carLogo;
			$imgData=array(
				'car_id' => $insertedId,
				'image_name'=>$this->session->carLogo,
				'is_primary'=> '1'
			);

			$this->db->insert('aa_cars_image',$imgData);

			$imageArr=$this->session->carGalleryImages;
			// pr($imageArr);die;
			foreach ($imageArr as $row) {
				$imgData=array(
					'car_id' => $insertedId,
					'image_name'=>"uploads/cars/".$row,
				);
				$this->db->insert('aa_cars_image',$imgData);
			}
			$this->session->unset_userdata('carLogo');
			$this->session->unset_userdata('carGalleryImages');
			if ($this->db->affected_rows() > 0) {
				return "added";
			} 
		}
	}

	function deleteCar($id)
	{
		$data=array('del_in'=>1);
		$this->db->where('car_id',$id);
		$this->db->update('aa_cars',$data);
		if($this->db->affected_rows() > 0)
		{
			return "deleted";
		}
	}

	// // used to check whether the ugiven id exists or not
	// function checkCarExists($id)
	// {
	// 	$id = dbQueryField('car_id', 'aa_cars', array("car_id" => $id));
	// 	if ($id != "") {
	// 		return true;
	// 	} else {
	// 		return false;
	// 	}
	// }


	// used to verify whether the user is the owner of post
	function verifyCar($id)
	{
		// $userId= $this->session->dashboard['dashUserId'];
		$userId = 1;

		$res=$this->db->select('car_id')->where('car_id',$id)->get('aa_cars')->row();
		if(isset($res))
		{
			$carUserId = dbQueryField('user_id', 'aa_cars', array('car_id' => $id));
			if ($userId == $carUserId) {
				return "true";
			} else {
				return "false";
			}
		}
		else
		{
			return "false";
		}
		

		
	}

	function deleteImageOfCar()
	{
		$id=$this->input->post('carId');
		$name=dbQueryField('image_name','aa_cars_image',array('image_id'=>$id));
		// pr($this->session->carGalleryImages);
		// echo $name."<br>";
		$name= str_replace("uploads/cars/", "", $name);

		$sessionImgs=$this->session->carGalleryImages;
		if(count($sessionImgs)>0)
		{
			$index = array_search($name,$sessionImgs);
			if($index !== FALSE){
				unset($sessionImgs[$index]);
			}
			session_unset('carGalleryImages');
			$this->session->set_userdata('carGalleryImages',$sessionImgs);

		}
		
		$this->db->where('image_id',$id)->delete('aa_cars_image');
		if ($this->db->affected_rows() > 0) {
			return "deleted";
		} 
	}


}

?>