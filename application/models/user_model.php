<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class user_model extends CI_Model{
    function checkUser($name,$password)
	{
		$query = $this->db->query("SELECT * from user_register where name='$name' AND password='$password'");
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
	}
    public function getUser($email){
        return $this->db->where('email',$email)->get('google_users')->row();
      }
      
      public function getNormalUser($email){
        return $this->db->where('email',$email)->get('user_register')->row();
      }
      
      public function getusername($id){
        return $this->db->where('id',$id)->get('user_register')->row();
      }
      public function createUser($data){
        $this->db->insert('google_users',$data);
      }
}
?>