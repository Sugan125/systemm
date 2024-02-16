<?php 
class Uploadmodel extends CI_Model {
	function upload_data($data)
	{
		$this->db->insert('images',$data);
	}

    public function getfiles($data, $table) {
        $this->db->where('name', $data['name']);
        $query = $this->db->get($table);
        return $query->result();
    }

      
}