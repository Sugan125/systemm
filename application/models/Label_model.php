<?php
class Label_model extends CI_Model {
    public function get_product_by_id($id)
    {
        return $this->db->get_where('products_label', ['id' => $id])->row_array();
    }
    public function get_all_products()
    {
        return $this->db->get('products_label')->result_array();
    }
    
    public function product_details() {
        $this->db->select('*');
        $this->db->from('products_label');
        $this->db->order_by('product_name', 'ASC');
        $this->db->limit(10); // Set limit here
        return $this->db->get()->result();
    }

    public function delete_data($data,$table){
      
        $this->db->where('id',$data['id']);
        $this->db->delete($table);

  
      }
      public function update_data($data,$table){

        $query = $this->db->query("select * from products_label where id=".$data['id']);
        
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

      public function insert_label($data,$table){
        $this->db->insert($table,$data);
      }
  
        public function count_search_results($keyword) {
          $this->db->like('product_name', $keyword);
          return $this->db->count_all_results('products_label');
      }
  
      public function search_products($keyword, $limit, $offset) {
        $this->db->like('product_name', $keyword);
        $this->db->limit($limit, $offset);
    
        $query = $this->db->get('products_label');
        return $query->result();
    }
  
    public function count_all_products() {
      return $this->db->count_all_results('products_label');
  }
  
  
   
    public function get_products($limit, $offset, $search_name = null, $search_price = null) {
      $this->db->where('status', 'active');
  
      if (!empty($search_name)) {
          $this->db->like('product_name', $search_name);
      }
     
      $this->db->limit($limit, $offset);
      return $this->db->get('products_label')->result();
  }
  
  public function get_product_count($search_name = null, $search_price = null) {
    $this->db->where('status', 'active');
  
      if (!empty($search_name)) {
          $this->db->like('product_name', $search_name);
      }
    
      return $this->db->count_all_results('products');
  }
  
}
?>
