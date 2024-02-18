<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class product_model extends CI_Model{
    public function product_details() {
        return $this->db->get('products')->result();
    }
    public function insert_product($data,$table){
        $this->db->insert($table,$data);
      }

      public function delete_data($data,$table){
      
        $this->db->where('id',$data['id']);
        $this->db->update($table,$data);
        
  
      }

      public function update_data($data,$table){

        $query = $this->db->query("select * from products where id=".$data['id']);
        
          if($query->num_rows()>0){
              return $query->result();
          }
          else{
              return NULL;
          }
        
  
      }

      
    public function update_datas($data,$table){
      
      $this->db->where('id',$data['id']);
      $this->db->update($table,$data);
      

    }
  
}