<?php
class Excel_export_model extends CI_Model
{
    function fetch_data() {
       
            $first_day_of_month = date('Y-m-01');
            $last_day_of_month = date('Y-m-t');
    
            $sql = "SELECT ord.*, orrr.*, uss.*,prod.product_id as prod_id,prod.product_name as product_name , orrr.created_date as created_date
                    FROM orders ord 
                    JOIN order_items orrr ON ord.id = orrr.order_id 
                    JOIN user_register uss ON ord.user_id = uss.id 
                    JOIN products prod ON orrr.product_id = prod.id
                    WHERE DATE(orrr.created_date) BETWEEN '$first_day_of_month' AND '$last_day_of_month'";
            $query = $this->db->query($sql);

          //  echo $this->db->last_query();

            return $query->result();

    }


    function fetch_data_by_date($sales_date) {
       
        $sql = "SELECT ord.*, orrr.*, uss.*, prod.product_id as prod_id, prod.product_name as product_name , orrr.created_date as created_date
        FROM orders ord 
        JOIN order_items orrr ON ord.id = orrr.order_id 
        JOIN user_register uss ON ord.user_id = uss.id 
        JOIN products prod ON orrr.product_id = prod.id
        WHERE DATE(orrr.created_date) = '$sales_date'";
        $query = $this->db->query($sql);
        return $query->result();

}
    

public function fetch_data_by_date_range($start_date, $end_date)
{
    $sql = "SELECT ord.*, orrr.*, uss.*, prod.product_id as prod_id, prod.product_name as product_name , orrr.created_date as created_date
            FROM orders ord 
            JOIN order_items orrr ON ord.id = orrr.order_id 
            JOIN user_register uss ON ord.user_id = uss.id 
            JOIN products prod ON orrr.product_id = prod.id
            WHERE DATE(orrr.created_date) BETWEEN '$start_date' AND '$end_date'";
    $query = $this->db->query($sql);
    return $query->result();
}

 
}