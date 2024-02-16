<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class user_model extends CI_Model{
    public function get_data($table){
        return $this->db->get($table);

    }

    public function insert_data($data,$table){
      $this->db->insert($table,$data);
      

    }

    public function is_email_exists($email) {
      $query = $this->db->query("SELECT * FROM user_register WHERE email='".$email."'");

      echo "Query: " . $this->db->last_query() . "<br>";
    echo "Rows found: " . $query->num_rows() . "<br>";
    
    
      if ($query->num_rows() >= 1) {
    return true;
      
  } else {
    return false;  
  }
  }

    public function update_datas($data,$table){
      
      $this->db->where('id',$data['id']);
      $this->db->update($table,$data);
      

    }

    public function delete_data($data,$table){
      
      $this->db->where('id',$data['id']);
      $this->db->delete($table,$data);
      

    }

    public function update_data($data,$table){

      $query = $this->db->query("select * from user_register where id=".$data['id']);
      
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return NULL;
        }
      

    }

    public function count_all_users() {
      return $this->db->count_all_results('user_register');
  }

  public function get_users($limit, $offset) {
      $this->db->limit($limit, $offset);
      return $this->db->get('user_register')->result();
  }

  public function count_search_results($keyword) {
      $this->db->like('name', $keyword);
      $this->db->or_like('email', $keyword);
      return $this->db->count_all_results('user_register');
  }

  public function search_users($keyword, $limit, $offset) {
    $this->db->like('name', $keyword);
    $this->db->or_like('email', $keyword);
    $this->db->limit($limit, $offset);

    $query = $this->db->get('user_register');
    //var_dump($this->db->last_query()); // Check the generated SQL query
    return $query->result();
}
      
function checkCurrentPassword($currentPassword)
	{
		$userid = $this->session->userdata('LoginSession')['id'];

    var_dump($userid);
    
		$query = $this->db->query("SELECT * from user_register WHERE id='$userid' AND password='$currentPassword' ");
		if($query->num_rows()==1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function updatePassword($password)
	{
		$userid = $this->session->userdata('LoginSession')['id'];

		$query = $this->db->query("update  user_register set password='$password' WHERE id='$userid' ");
		
	}


//login

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



  function validateEmail($email)
	{
		$query = $this->db->query("SELECT * FROM user_register WHERE email='$email'");
		if($query->num_rows() == 1)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}


	
  public function changepswd($email, $newPassword) 
  {
    
      $this->db->where('email', $email);
     $query = $this->db->get('user_register');

     if ($query->num_rows() > 0)
     {
         $userData = $query->row();
         
         $userData->password = $newPassword;

         $this->db->where('email', $email);
         $this->db->update('user_register', ['password' => $newPassword]);

         return true; 
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

//  public function fecth_name($email) {
//   $query = $this->db->query("SELECT name FROM user_register WHERE email='".$email."'");
//   if ($query->num_rows() == 1) {
//       return $query->row()->name;  // Return only the 'name' column value
//   } else {
//       return false;
//   }
// }

public function update_profile_pic($user_id, $filename) {
  $data = array(
      'profile_img' => $filename,
  );

  $this->db->where('id', $user_id);
  $this->db->update('user_register', $data);
}



public function count_all_user_role() {
  
      $this->db->where('access !=', '');
  

  return $this->db->count_all_results('user_register');
}


public function get_roleusers($limit, $offset) {
  $this->db->where('access !=', '');
  $this->db->limit($limit, $offset);
  return $this->db->get('user_register')->result();
}


public function get_roleuserss() {
 
  return $this->db->get('user_register')->result();
}

public function createrole($data,$table){
 
  $this->db->where('name', $data['name']);
  $this->db->update('user_register', $data);
  
  // Get the last executed query
  $query = $this->db->last_query();

  // Echo the query
  echo $query;

}

public function deleteaccess_data($data, $table) {
  $update_data = array('access' => '');
  $this->db->where('id', $data['id']);
  $this->db->update($table, $update_data);
}


public function countaccess_search_results($keyword) {
  $this->db->where('access !=', '');
  $this->db->like('name', $keyword);
  return $this->db->count_all_results('user_register');
}

public function searchaccess_users($keyword, $limit, $offset) {
  $this->db->where('access !=', '');
$this->db->like('name', $keyword);
$this->db->limit($limit, $offset);

$query = $this->db->get('user_register');
//var_dump($this->db->last_query()); // Check the generated SQL query
return $query->result();
}

}