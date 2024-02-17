<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class product_model extends CI_Model{
    public function product_details() {
        return $this->db->get('products')->result();
    }
    public function insert_product($data,$table){
        $this->db->insert($table,$data);
        
  
      }
}