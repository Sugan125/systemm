<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Print_log_model extends CI_Model
{
    /* ------------------------------------------------------------
     * WRITING THE LOG
     * ---------------------------------------------------------- */

    // One row per print: type, delivery date, who (id + name), when, IP.
    // The 3rd parameter is ignored; it only keeps your existing controller lines working.
    public function log_print($doc_type, $delivery_date, $orders = null)
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $delivery_date)) { return false; }

        $login   = $this->session->userdata('LoginSession');
        $user_id = isset($login['id']) ? (int) $login['id'] : null;

        $name = null;
        if ($user_id) {
            $u = $this->db->select('name')->where('id', $user_id)->get('user_register')->row();
            $name = $u ? $u->name : null;
        }

        return $this->db->insert('print_logs', array(
            'document_type'   => $doc_type,
            'delivery_date'   => $delivery_date,
            'printed_by'      => $user_id,
            'printed_by_name' => $name,
            'printed_at'      => date('Y-m-d H:i:s'),
            'ip_address'      => $this->input->ip_address(),
        ));
    }

    // For single-order prints (printDiv / printadmin)
    public function log_order_print($doc_type, $order_id)
    {
        $row = $this->db->select('delivery_date')
                        ->where('id', (int) $order_id)
                        ->get('orders')->row();
        if (!$row) { return false; }
        return $this->log_print($doc_type, $row->delivery_date);
    }

    /* ------------------------------------------------------------
     * READING THE LOG (used by the Print Logs page)
     * ---------------------------------------------------------- */

    // Applies the page filters to the query being built
    private function _apply_filters($f)
    {
        if (!empty($f['printed_from']))  { $this->db->where('printed_at >=', $f['printed_from'] . ' 00:00:00'); }
        if (!empty($f['printed_to']))    { $this->db->where('printed_at <=', $f['printed_to'] . ' 23:59:59'); }
        if (!empty($f['delivery_from'])) { $this->db->where('delivery_date >=', $f['delivery_from']); }
        if (!empty($f['delivery_to']))   { $this->db->where('delivery_date <=', $f['delivery_to']); }
        if (!empty($f['doc_type']))      { $this->db->where('document_type', $f['doc_type']); }
        if (!empty($f['user_id']))       { $this->db->where('printed_by', (int) $f['user_id']); }
        if (!empty($f['early']))         { $this->db->where('DATEDIFF(delivery_date, DATE(printed_at)) > 1', null, false); }
    }

    public function get_logs($f, $limit, $offset)
    {
        $this->db->select('id, document_type, delivery_date, printed_by, printed_by_name, printed_at, ip_address,
                           DATEDIFF(delivery_date, DATE(printed_at)) AS days_before', false);
        $this->db->from('print_logs');
        $this->_apply_filters($f);
        $this->db->order_by('printed_at', 'DESC');
        $this->db->order_by('id', 'DESC');
        $this->db->limit((int) $limit, (int) $offset);
        return $this->db->get()->result();
    }

    // Totals for the filtered result: total prints, early prints, distinct users
    public function get_summary($f)
    {
        $this->db->select('COUNT(*) AS total,
                           COALESCE(SUM(DATEDIFF(delivery_date, DATE(printed_at)) > 1), 0) AS early,
                           COUNT(DISTINCT printed_by) AS users', false);
        $this->db->from('print_logs');
        $this->_apply_filters($f);
        $row = $this->db->get()->row();

        return array(
            'total' => (int) $row->total,
            'early' => (int) $row->early,
            'users' => (int) $row->users,
        );
    }

    // People who appear in the log (for the "Printed by" dropdown)
    public function get_users()
    {
        return $this->db->query(
            "SELECT printed_by AS id, MAX(printed_by_name) AS name
               FROM print_logs
              WHERE printed_by IS NOT NULL
              GROUP BY printed_by
              ORDER BY name ASC"
        )->result();
    }
}
