<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Modelwelcome', 'model');
        error_reporting(E_ALL);
    }

    public function index()
    {
//        $this->session->sess_destroy('access_token'); die;
//            echo $this->input->get('code');die;
        // if user visits for the first time
        if ($this->input->get('code')) {
            $code = $this->input->get('code');
            $tokenData = $this->model->GetAccessToken(CLIENT_ID, CLIENT_REDIRECT_URL, CLIENT_SECRET, $code);
            $this->session->set_userdata('access_token', $tokenData['access_token']);
            redirect('welcome/getEvents');
        }
        if ($this->session->access_token) {
            redirect('welcome/getEvents');
        }
        else if ($this->model->userExists()) {
            $accessDetails = $this->model->getAccessTokenDetails();
            $refreshedToken = $this->model->GetRefreshedAccessToken(CLIENT_ID, $accessDetails->refresh_token, CLIENT_SECRET);
            $this->session->set_userdata('access_token', $refreshedToken['access_token']);
            redirect('welcome/getEvents');
        }
        else {
            $this->load->view('calendar');
        }
    }

    public function getEvents()
    {
        if ($this->session->access_token) {
            $accessDetails = $this->model->getAccessTokenDetails();
            if (!$this->checkToken($accessDetails)) {
                $refreshedToken = $this->model->GetRefreshedAccessToken(CLIENT_ID, $accessDetails->refresh_token, CLIENT_SECRET);
                $this->session->set_userdata('access_token', $refreshedToken['access_token']);
            }
            $events = $this->model->getCalendarEvents();
            $eventsArr = array();
            $arrCounter = 0;
            foreach ($events['items'] as $row) {
                $eventsArr[$arrCounter]['id'] = $row['id'];
                $eventsArr[$arrCounter]['title'] = $row['summary'];
                $eventsArr[$arrCounter]['fullStartDateTime'] = $row['start']['dateTime'];
                $eventsArr[$arrCounter]['fullEndDateTime'] = $row['end']['dateTime'];
                $eventsArr[$arrCounter]['start'] = substr($row['start']['dateTime'], 0, 10);
                $eventsArr[$arrCounter]['end'] = substr($row['end']['dateTime'], 0, 10);
                $arrCounter++;
            }
            $data['calendarEvents'] = $eventsArr;
            $this->load->view('list', $data);
        } else {
            redirect('welcome');
        }
    }

    public function addEvent()
    {

        $access_token = $this->session->access_token;
        $calendarId = 'primary';
        $eventTitle = $this->input->post('eventTitle');
        $fullDayEvent = 0;
        $timezone = $this->model->GetUserCalendarTimezone($access_token);

        $startDate = str_replace(' ', 'T', $this->input->post('startDate'));
        $startDate .= ':00';
        $endDate = str_replace(' ', 'T', $this->input->post('endDate'));
        $endDate .= ':00';
        $eventTime = array('start_time' => $startDate, 'end_time' => $endDate);
        $id = $this->model->CreateCalendarEvent($calendarId, $eventTitle, $fullDayEvent, $eventTime, $timezone, $access_token);
        $data['response'] = "Added";
        $data['eventId'] = $id;
        echo json_encode($data);
    }

    public function updateCalendarEvent()
    {
        $access_token = $this->session->access_token;
        $timezone = $this->model->GetUserCalendarTimezone($this->session->access_token);
        $calendarId = 'primary';
        $eventId = $this->input->post('eventId');
        $fullDayEvent = 0;
        // get event details by Event Id
        $events = $this->model->getCalendarEventById('primary', $eventId);
//        echo "<pre>";
//        print_r($events);die;
        $eventTitle = $events['summary'];

        $eventType = $this->input->post('eventType');
        if ($eventType == "resize") {
            $startDate = $this->input->post('startDate');
            $endDate = $this->input->post('endDate');
            $startmodified = $this->getOnlyTime($events['start']['dateTime']);
            $endmodified = $this->getOnlyTime($events['end']['dateTime']);
            $fullStartDate = $startDate . "T" . $startmodified;
            $fullEndDate = $endDate . "T" . $endmodified;
            $eventTime = array('start_time' => $fullStartDate, 'end_time' => $fullEndDate);
        } else if ($eventType == "drop") {
            $movedDate = $this->input->post('movedDate');
            $startmodified = $this->getOnlyTime($events['start']['dateTime']);
            $endmodified = $this->getOnlyTime($events['end']['dateTime']);
            $fullStartDate = $movedDate . "T" . $startmodified;
            $fullEndDate = $movedDate . "T" . $endmodified;
            $eventTime = array('start_time' => $fullStartDate, 'end_time' => $fullEndDate);
        } else if ($eventType == "eventUpdate") {
            $eventTitle = $this->input->post('eventTitle');
            $startDate = str_replace(' ', 'T', $this->input->post('startDate'));
            $startDate .= ':00';
            $endDate = str_replace(' ', 'T', $this->input->post('endDate'));
            $endDate .= ':00';
            $eventTime = array('start_time' => $startDate, 'end_time' => $endDate);
        }
        //Update Event
        $this->model->UpdateCalendarEvent($eventId, $calendarId, $eventTitle, $fullDayEvent, $eventTime, $timezone, $access_token);
        $data['response'] = "updated";
        echo json_encode($data);
    }

    public function deleteEvent()
    {
        $eventId = $this->input->post('eventId');
//        $eventId='1rivdnnar63gu8hni7mvkbstli';
        $result = $this->model->DeleteCalendarEvent($eventId, 'primary', $this->session->access_token);
        if ($result) {
            $data['response'] = "deleted";
            echo json_encode($data);
        }
    }

    public function checkToken($accessDetails)
    {
        $datetime1 = new DateTime($accessDetails->access_expiry_time);
        $datetime2 = new DateTime('now');
        $interval = $datetime1->diff($datetime2);
        if ($interval->format('%i') > 58 || $interval->format('%h') > 0 || $interval->format('%a') > 0 || $interval->format('%m') > 0 || $interval->format('%y') > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function getOnlyTime($time)
    {
        $afterT = explode('T', $time);
        $startTime = explode('+', $afterT[1]);
        return $startTime['0'];
    }

}
