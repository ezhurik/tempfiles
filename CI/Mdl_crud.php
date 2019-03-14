<?php

class Mdl_crud extends CI_Model
{

    public function getAllData()
    {
        return $reslut = $this->db->where('users.status', '1')
            ->get('users')
            ->result_array();
    }

    public function getUserData($id)
    {
        return $this->db->where('user_id', $id)
            ->get('users')->row();
    }

    public function getUserImages($id){
        return $this->db->where('user_id', $id)
            ->get('user_image')->result_array();
    }

    public function save(){
        $data=array(
            'username'=>$this->input->post('username'),
            'company_name'=>$this->input->post('company_name'),
            'company_location'=>json_encode($this->input->post('location[]')),
            'roles'=>json_encode($this->input->post('roles[]'))
        );
        if($this->input->post('userId')){
            $this->db->where('user_id',$this->input->post('userId'))
            ->update('users',$data);
            $imageArr=$this->session->sessImages;
            if(isset($imageArr))
            {
                foreach ($imageArr as $row) {
                    $imgData=array(
                        'user_id' => $this->input->post('userId'),
                        'image'=>"images/uploads/".$row,
                    );
                    $this->db->insert('user_image',$imgData);
                }
            }
            $this->session->unset_userdata('sessImages');
        }
        else{
            $this->db->insert('users',$data);
            if ($this->db->affected_rows() > 0) {
                $insertedId = $this->db->insert_id();
            }
            $imageArr=$this->session->sessImages;
            foreach ($imageArr as $row) {
                $imgData=array(
                    'user_id' => $insertedId,
                    'image'=>"images/uploads/".$row,
                );
                $this->db->insert('user_image',$imgData);
            }
            $this->session->unset_userdata('sessImages');
        }
    }

}

?>