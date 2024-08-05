<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Antrian_model extends CI_Model {

    // Fungsi untuk mendapatkan semua data antrian
    public function get_antrian() {
        $this->db->order_by('tgl_antrian', 'ASC');
        $query = $this->db->get('antrian');
        return $query->result_array();
    }

    // Fungsi untuk mendapatkan data antrian berdasarkan tanggal
    public function get_antrian_by_date($date, $selected_poli) {
        $this->db->where('DATE(tgl_antrian)', $date);
        $this->db->where('kode_poli', $selected_poli);
        $this->db->order_by('tgl_antrian', 'ASC');
        $query = $this->db->get('antrian');
        return $query->result_array();
    }

    public function get_poli() {
        $query = $this->db->get('kategori_poli');
        return $query->result_array();
    }
}
?>
