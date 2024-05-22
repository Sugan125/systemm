<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class product_model extends CI_Model{
  public function product_details() {
    $this->db->select('*');
    $this->db->from('products');
    $this->db->order_by('prod_category', 'ASC');
    $this->db->order_by('product_name', 'ASC');
    $this->db->limit(10); // Set limit here
    return $this->db->get()->result();
}

public function product_details_user() {
  $this->db->select('*');
  $this->db->from('products');
  $this->db->order_by('prod_category', 'ASC');
  $this->db->order_by('product_name', 'ASC');
 // $this->db->limit(10); // Set limit here
  return $this->db->get()->result();
}


    public function insert_product($data,$table){
        $this->db->insert($table,$data);
      }

      public function delete_data($data,$table){
      
        $this->db->where('id',$data['id']);
        $this->db->delete($table);

  
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

      public function count_search_results($keyword) {
        $this->db->like('product_name', $keyword);
        $this->db->or_like('prod_category', $keyword);
        $this->db->or_like('product_id', $keyword);
        return $this->db->count_all_results('products');
    }

    public function search_products($keyword, $limit, $offset) {
      $this->db->like('product_name', $keyword);
      $this->db->or_like('prod_category', $keyword);
      $this->db->or_like('product_id', $keyword);
      $this->db->limit($limit, $offset);
  
      $query = $this->db->get('products');
      //var_dump($this->db->last_query()); // Check the generated SQL query
      return $query->result();
  }

  public function count_all_products() {
    return $this->db->count_all_results('products');
}

    public function update_datas($data,$table){
      
      $this->db->where('id',$data['id']);
      $this->db->update($table,$data);
      

    }

    public function insert_import($data) {
 
      $res = $this->db->insert_batch('products',$data);
      if($res){
          return TRUE;
      }else{
          return FALSE;
      }

  }

  public function get_products() {
    //     $this->db->limit($limit, $offset);
          $this->db->where('active', 1);
         return $this->db->get('products')->result();
     }
  
}