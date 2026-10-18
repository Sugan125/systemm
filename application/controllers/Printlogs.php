<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Printlogs extends CI_Controller
{
    private $doc_types = array(
        'production_list'  => 'Production List',
        'packing_list'     => 'Packing List',
        'invoice_combined' => 'Invoice (Combined)',
        'invoice_separate' => 'Invoice (Separate)',
        'invoice_single'   => 'Invoice (Single)',
        'do_combined'      => 'DO (Combined)',
        'do_separate'      => 'DO (Separate)',
    );

    private $per_page = 25;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('pagination');
        $this->load->model('print_log_model');

        // Only logged-in staff may open this page
        if (!$this->session->userdata('LoginSession')) {
            redirect('Login');
        }
    }

    /* ---------- Read and clean the filters from the URL ---------- */
    private function _filters()
    {
        $get = $this->input->get(null, true);

        // Nothing in the URL yet: start with the last 7 days
        if (empty($get)) {
            return array(
                'printed_from'  => date('Y-m-d', strtotime('-6 days')),
                'printed_to'    => date('Y-m-d'),
                'delivery_from' => '',
                'delivery_to'   => '',
                'doc_type'      => '',
                'user_id'       => '',
                'early'         => '',
            );
        }

        $date = function ($v) {
            return (is_string($v) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $v)) ? $v : '';
        };

        $doc_type = isset($get['doc_type']) ? $get['doc_type'] : '';
        if (!isset($this->doc_types[$doc_type])) { $doc_type = ''; }

        return array(
            'printed_from'  => $date(isset($get['printed_from']) ? $get['printed_from'] : ''),
            'printed_to'    => $date(isset($get['printed_to']) ? $get['printed_to'] : ''),
            'delivery_from' => $date(isset($get['delivery_from']) ? $get['delivery_from'] : ''),
            'delivery_to'   => $date(isset($get['delivery_to']) ? $get['delivery_to'] : ''),
            'doc_type'      => $doc_type,
            'user_id'       => !empty($get['user_id']) ? (string) (int) $get['user_id'] : '',
            'early'         => !empty($get['early']) ? '1' : '',
        );
    }

    /* ---------- The page ---------- */
    public function index()
    {
        $filters = $this->_filters();
        $page    = max(1, (int) $this->input->get('page'));
        $offset  = ($page - 1) * $this->per_page;

        $summary = $this->print_log_model->get_summary($filters);
        $rows    = $this->print_log_model->get_logs($filters, $this->per_page, $offset);

        // Pagination (keeps the current filters in every page link)
        $this->pagination->initialize(array(
            'base_url'             => site_url('printlogs') . '?' . http_build_query($filters),
            'total_rows'           => $summary['total'],
            'per_page'             => $this->per_page,
            'page_query_string'    => true,
            'query_string_segment' => 'page',
            'use_page_numbers'     => true,
            'reuse_query_string'   => false,
            'num_links'            => 2,
            'full_tag_open'        => '<ul class="pl-pager">',
            'full_tag_close'       => '</ul>',
            'first_link'           => 'First',
            'last_link'            => 'Last',
            'prev_link'            => 'Prev',
            'next_link'            => 'Next',
            'first_tag_open'       => '<li>',
            'first_tag_close'      => '</li>',
            'last_tag_open'        => '<li>',
            'last_tag_close'       => '</li>',
            'prev_tag_open'        => '<li>',
            'prev_tag_close'       => '</li>',
            'next_tag_open'        => '<li>',
            'next_tag_close'       => '</li>',
            'num_tag_open'         => '<li>',
            'num_tag_close'        => '</li>',
            'cur_tag_open'         => '<li class="active"><span>',
            'cur_tag_close'        => '</span></li>',
        ));

        $data = array(
            'title'      => 'Print Logs',
            'filters'    => $filters,
            'summary'    => $summary,
            'rows'       => $rows,
            'users_list' => $this->print_log_model->get_users(),
            'doc_types'  => $this->doc_types,
            'pagination' => $this->pagination->create_links(),
            'page'       => $page,
            'per_page'   => $this->per_page,
            'offset'     => $offset,
            'export_url' => site_url('printlogs/export') . '?' . http_build_query($filters),
        );

        $loginuser = $this->session->userdata('LoginSession');
        $user      = $this->session->userdata('user_register');
        $users     = $this->session->userdata('normal_user');

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php', array(
            'user' => $user, 'users' => $users, 'data' => $data, 'loginuser' => $loginuser,
        ));
        $this->load->view('printlogs/index.php', $data);
        $this->load->view('template/footer.php');
    }

    /* ---------- CSV download (uses the same filters) ---------- */
    public function export()
    {
        $filters = $this->_filters();
        $rows    = $this->print_log_model->get_logs($filters, 50000, 0);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="print_logs_' . date('Ymd_His') . '.csv"');

        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF"); // lets Excel read the file as UTF-8
        fputcsv($out, array('ID', 'Document', 'Delivery date', 'Printed date', 'Printed time',
                            'Days before delivery', 'Timing', 'User ID', 'User name', 'IP address'));

        foreach ($rows as $r) {
            $d = (int) $r->days_before;
            $timing = $d > 1 ? 'Early' : ($d === 1 ? 'Day before' : ($d === 0 ? 'Same day' : 'After delivery'));
            fputcsv($out, array(
                $r->id,
                isset($this->doc_types[$r->document_type]) ? $this->doc_types[$r->document_type] : $r->document_type,
                $r->delivery_date,
                date('Y-m-d', strtotime($r->printed_at)),
                date('H:i:s', strtotime($r->printed_at)),
                $d,
                $timing,
                $r->printed_by,
                $r->printed_by_name,
                $r->ip_address,
            ));
        }
        fclose($out);
    }
}
