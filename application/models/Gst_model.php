<?php
class Gst_model extends CI_Model {

    public function get_gst() {
        return $this->db->get('gst_master')->row();
    }

    public function update_gst($gst_percentage) {
        $this->db->update('gst_master', ['gst_percentage' => $gst_percentage]);
        return $this->db->affected_rows() > 0;
    }
}
?>
