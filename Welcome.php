<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct(){
	    parent::__construct();
        $this->load->model('Modelwelcome','model');
    }

    public function index()
	{
        $this->load->view('calendar');
	}

	public function getEvents(){
        $code = $this->input->get('code');
        $tokenData = $this->model->GetAccessToken(CLIENT_ID, CLIENT_REDIRECT_URL, CLIENT_SECRET, $code);
        $this->session->set_userdata('access_token',$tokenData['access_token']);
        $events=$this->model->getCalendarEvents();
        $eventsArr=array();
        $arrCounter=0;
        foreach($events['items'] as $row){
            $eventsArr[$arrCounter]['id'] = $row['id'];
            $eventsArr[$arrCounter]['title'] = $row['summary'];
            $eventsArr[$arrCounter]['start'] = $row['start']['dateTime'];
            $eventsArr[$arrCounter]['end'] = $row['end']['dateTime'];
            $arrCounter++;
        }
//        echo "<pre>";
//        print_r($eventsArr);

        $data['calendarEvents']=$eventsArr;
        $this->load->view('list',$data);
    }

}
