<?php

class Modelwelcome extends CI_Model
{
    public function GetAccessToken($client_id, $redirect_uri, $client_secret, $code)
    {
        $url = 'https://accounts.google.com/o/oauth2/token';
        $curlPost = 'client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $client_secret . '&code=' . $code . '&grant_type=authorization_code';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 200)
            throw new Exception('Error : Failed to receieve access token');

//        $userId=$this->session->userId;
        $userId=1;
        if(isset($data['refresh_token'])){
            $tokkenArray=array(
                'user_id'=>$userId,
                'access_token'=>$data['access_token'],
                'access_expiry_time'=>date('Y-m-d H:i:s'),
                'refresh_token'=>$data['refresh_token']
            );
        }
        else{
            $tokkenArray=array(
                'user_id'=>$userId,
                'access_token'=>$data['access_token'],
                'access_expiry_time'=>date('Y-m-d H:i:s')
            );
        }

        $userExists = $this->db->select('user_id')
            ->where('user_id',$userId)
            ->get('calendar')->row();
        if(count($userExists)> 0){
            $this->db->update('calendar',$tokkenArray);
        }
        else{
            $this->db->insert('calendar',$tokkenArray);
        }
        return $data;
    }

    public function GetRefreshedAccessToken($client_id, $refresh_token, $client_secret) {
        $url_token = 'https://www.googleapis.com/oauth2/v4/token';
        $curlPost = 'client_id=' . $client_id . '&client_secret=' . $client_secret . '&refresh_token='. $refresh_token . '&grant_type=refresh_token';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = json_decode(curl_exec($ch), true);	//print_r($data);
        $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        if($http_code != 200)
            throw new Exception('Error : Failed to refresh access token');

        $userId=1;
        $tokkenArray=array(
            'access_token'=>$data['access_token'],
            'access_expiry_time'=>date('Y-m-d H:i:s'),
        );
        $this->db->where('user_id',$userId)
            ->update('calendar',$tokkenArray);
        return $data;
    }

    public function getAccessTokenDetails(){
//        $id=$this->session->user_id;
        $id=1;
        return $this->db->where('user_id',$id)
        ->get('calendar')->row();
    }

    public function GetUserCalendarTimezone($access_token)
    {
        $url_settings = 'https://www.googleapis.com/calendar/v3/users/me/settings/timezone';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_settings);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $data = json_decode(curl_exec($ch), true); //echo '<pre>';print_r($data);echo '</pre>';
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 200)
            throw new Exception('Error : Failed to get timezone');

        return $data['value'];
    }

    public function getCalendarEventById($calendarId = 'primary',$eventId)
    {
        $url_events = 'https://www.googleapis.com/calendar/v3/calendars/' . $calendarId . '/events/'.$eventId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_events);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->session->access_token, 'Content-Type: application/json'));
//        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curlPost));
        $data = json_decode(curl_exec($ch), true);
        return $data;
    }

    public function GetCalendarsList($access_token)
    {
        $url_parameters = array();

        $url_parameters['fields'] = 'items(id,summary,timeZone)';
        $url_parameters['minAccessRole'] = 'owner';

        $url_calendars = 'https://www.googleapis.com/calendar/v3/users/me/calendarList?' . http_build_query($url_parameters);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_calendars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $data = json_decode(curl_exec($ch), true); //echo '<pre>';print_r($data);echo '</pre>';
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 200)
            throw new Exception('Error : Failed to get calendars list');

        return $data['items'];
    }

    public function CreateCalendarEvent($calendar_id, $summary, $all_day, $event_time, $event_timezone, $access_token) {
        $url_events = 'https://www.googleapis.com/calendar/v3/calendars/' . $calendar_id . '/events';

//        $curlPost['description']=$description;
        $curlPost = array('summary' => $summary);
        if($all_day == 1) {
            $curlPost['start'] = array('date' => $event_time['event_date']);
            $curlPost['end'] = array('date' => $event_time['event_date']);
        }
        else {
            $curlPost['start'] = array('dateTime' => $event_time['start_time'], 'timeZone' => $event_timezone);
            $curlPost['end'] = array('dateTime' => $event_time['end_time'], 'timeZone' => $event_timezone);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_events);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token, 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curlPost));
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        if($http_code != 200)
            throw new Exception('Error : Failed to create event');

        return $data['id'];
    }

    public function getCalendarEvents($calendarId = 'primary')
    {
        $timeMin = date("c", strtotime(date('Y-m-01 ') . ' 00:00:00'));
        $timeMax = date("c", strtotime(date('Y-m-t ') . ' 23:59:59'));
        $optParams = array(
//            'maxResults'   => $maxResults,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => $timeMin,
            'timeMax' => $timeMax,
            'timeZone' => 'Asia/Kolkata',

        );
        $url_events = 'https://www.googleapis.com/calendar/v3/calendars/' . $calendarId . '/events';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_events);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->session->access_token, 'Content-Type: application/json'));
//        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curlPost));
        $data = json_decode(curl_exec($ch), true);
//        echo "<pre>";
//        print_r($data);die;
        return $data;
    }

    public function UpdateCalendarEvent($event_id, $calendar_id, $summary, $all_day, $event_time, $event_timezone, $access_token)
    {
        $url_events = 'https://www.googleapis.com/calendar/v3/calendars/' . $calendar_id . '/events/' . $event_id;
        $curlPost = array('summary' => $summary);
        if($all_day == 1) {
            $curlPost['start'] = array('date' => $event_time['event_date']);
            $curlPost['end'] = array('date' => $event_time['event_date']);
        }
        else {
            $curlPost['start'] = array('dateTime' => $event_time['start_time'], 'timeZone' => $event_timezone);
            $curlPost['end'] = array('dateTime' => $event_time['end_time'], 'timeZone' => $event_timezone);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_events);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token, 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curlPost));
        $data = json_decode(curl_exec($ch), true);

        $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        if($http_code != 200)
            throw new Exception('Error : Failed to update event');
        else
            return true;
    }

    //drag update
    public function UpdateCalendaronDrag($event_id, $calendar_id, $summary, $all_day, $event_time, $event_timezone, $access_token)
    {
        $url_events = 'https://www.googleapis.com/calendar/v3/calendars/' . $calendar_id . '/events/' . $event_id;
        $curlPost = array('summary' => $summary);
        if($all_day == 1) {
            $curlPost['start'] = array('date' => $event_time['event_date']);
            $curlPost['end'] = array('date' => $event_time['event_date']);
        }
        else {
            $curlPost['start'] = array('dateTime' => $event_time['start_time'], 'timeZone' => $event_timezone);
            $curlPost['end'] = array('dateTime' => $event_time['end_time'], 'timeZone' => $event_timezone);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_events);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token, 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curlPost));
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        if($http_code != 200)
            throw new Exception('Error : Failed to update event');
        else
            return true;
    }

    public function DeleteCalendarEvent($event_id, $calendar_id, $access_token) {
        $url_events = 'https://www.googleapis.com/calendar/v3/calendars/' . $calendar_id . '/events/' . $event_id;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_events);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token, 'Content-Type: application/json'));
        $data = json_decode(curl_exec($ch), true);

        $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        if($http_code != 204)
            throw new Exception('Error : Failed to delete event');
        else
            return true;
    }

    public function getDriverId(){
        $email = $this->session->contact_email;
        $userInfo = $this->db->select('admin_rp_id')
            ->where('contact_email',$email)
            ->get('admin_rp')->row();
        return $userInfo->admin_rp_id;
    }

    function userExists(){
//        $userId = $this->getDriverId();
        $userId = 1;
        $userExists = $this->db->select('user_id')
            ->where('user_id',$userId)
            ->get('calendar')->row();
        if(count($userExists)> 0){
            return true;
        }
        else{
            return false;
        }
    }

}

?>