<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include('Super.php');

class Antrian_fcfs extends Super {
    
    public function __construct() {
        parent::__construct();
        $this->tema           = "flexigrid"; /** datatables / flexigrid **/
        $this->nama_view      = "Statistik Antrian FCFS";

        $this->load->database();
        $this->load->model('Antrian_model');
    }

    public function index() {
        $db_host = 'localhost';
        $db_user = 'root';
        $db_pass = '';
        $db_name = 'db_antrian';

        $db = new mysqli($db_host, $db_user, $db_pass, $db_name);

        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }
        $data = [];
        $poli_names = [
            1 => "Poli Dalam",
            2 => "Poli Spesialis Jantung",
            3 => "Poli Spesialis Mata",
            4 => "Poli Spesialis Kandungan",
            5 => "Poli Spesialis THT",
            6 => "poli Spesialis Gigi"
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['poliku'])) {
            $id_antrian_poli = $_POST['poliku'];
            $now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
            
            list($tanggal_input, $waktu_input) = explode(" - ", $id_antrian_poli);
            $tanggal_mysql = DateTime::createFromFormat('d/m/Y', $tanggal_input)->format('Y-m-d');
            $waktu_mysql = DateTime::createFromFormat('H:i:s', $waktu_input)->format('H:i:s');
            $tgl_mulai = $now->format('H:i');
            
            // Debug: cek apakah data POST diterima
            error_log("id_antrian_poli: " . $id_antrian_poli);
            
            // Query update
            $query = "UPDATE antrian SET tgl_mulai = ? WHERE DATE(tgl_antrian) = DATE(?) AND TIME(tgl_antrian) = TIME(?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param('sss', $tgl_mulai, $tanggal_mysql, $waktu_mysql);
            
            // Debug: cek apakah statement sudah disiapkan dengan benar
            if ($stmt === false) {
                error_log("Statement prepare failed: " . $db->error);
            }
            
            if ($stmt->execute()) {
                // Debug: cek apakah eksekusi query berhasil
                error_log("Record updated successfully");
            } else {
                // Debug: cek jika terjadi kesalahan saat eksekusi query
                error_log("Error updating record: " . $stmt->error);
            }
            
            $stmt->close();
            $db->close();
            
            // Redirect kembali ke halaman utama
            header("Location: /kampus-antrian-klinik/admin/antrian_poli");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_antrian_poli'])) {
            $id_antrian_poli = $_POST['id_antrian_poli'];
		    $now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
		    $tgl_selesai = $now->format('Y-m-d H:i:s');
            list($tanggal_input, $waktu_input) = explode(" - ", $id_antrian_poli);
            $tanggal_mysql = DateTime::createFromFormat('d/m/Y', $tanggal_input)->format('Y-m-d');
            $waktu_mysql = DateTime::createFromFormat('H:i:s', $waktu_input)->format('H:i:s');
            $tanggal_waktu_mysql = $tanggal_mysql . ' ' . $waktu_mysql;
        
            // Debug: cek apakah data POST diterima
            error_log("id_antrian_poli: " . $id_antrian_poli);
        
            // Query update
            $query = "UPDATE antrian SET tgl_selesai = ? WHERE DATE(tgl_antrian) = DATE(?) AND TIME(tgl_antrian) = TIME(?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param('sss', $tgl_selesai, $tanggal_mysql, $waktu_mysql);
        
            // Debug: cek apakah statement sudah disiapkan dengan benar
            if ($stmt === false) {
                error_log("Statement prepare failed: " . $db->error);
            }
        
            if ($stmt->execute()) {
                // Debug: cek apakah eksekusi query berhasil
                error_log("Record updated successfully");
            } else {
                // Debug: cek jika terjadi kesalahan saat eksekusi query
                error_log("Error updating record: " . $stmt->error);
            }
        
            $stmt->close();
            $db->close();
        
            // Redirect kembali ke halaman utama
            header("Location: /kampus-antrian-klinik/admin/antrian_poli");
            exit;
        }

        // Ambil tanggal dari input pengguna
        $selected_date = $this->input->get('tanggal');
        $selected_poli = $this->input->get('get_poli');
        $get_poli =  $this->Antrian_model->get_poli();

        
        // Set nama_view berdasarkan poliku
        if ($selected_poli && array_key_exists($selected_poli, $poli_names)) {
            $this->nama_view = "Statistik Antrian FCFS " . $poli_names[$selected_poli];
        } else {
            $this->nama_view = "Statistik Antrian FCFS";
        }
        
        // Ambil data antrian berdasarkan tanggal yang dipilih
        if ($selected_date) {
            $antrian = $this->Antrian_model->get_antrian_by_date($selected_date, $selected_poli);
        } else {
            $antrian = $this->Antrian_model->get_antrian();
        }

        // Menghitung statistik FCFS
        $antrian_statistik = $this->calculate_fcfs($antrian);
        
        // Menghitung rata-rata waktu tunggu dan waktu selesai
        $avg_times = $this->calculate_average_times($antrian_statistik);
        
        $data = array_merge($data, $this->generateBreadcumbs());
        $data = array_merge($data, $this->generateData());
        $data['antrian_statistik'] = $antrian_statistik;
        // $data['avg_waiting_time'] = $avg_times['avg_waiting_time'];
        $data['avg_turnaround_time'] = $avg_times['avg_turnaround_time'];
        $data['get_poli'] = $get_poli;
        $data['page'] = 'v_antrian_statistik_view';

        // Memuat view dan mengirim data
        $this->load->view('admin/'.$this->session->userdata('theme').'/v_index', $data);
    }


    private function calculate_fcfs($antrian) {
    $waiting_time = 0;
    $current_time = 0;

    $statistik = [];

    foreach ($antrian as $row) {
        $arrival_time = strtotime($row['tgl_antrian']); // waktu tiba dalam detik
        $finish_time = strtotime($row['tgl_selesai']); // waktu selesai dalam detik
        $tgl_mulai = strtotime($row['tgl_mulai']);
        
        // Debug: cek nilai waktu
        error_log("tgl_antrian: " . $row['tgl_antrian'] . " => " . $arrival_time);
        error_log("tgl_selesai: " . $row['tgl_selesai'] . " => " . $finish_time);
        error_log("tgl_mulai: " . $row['tgl_mulai'] . " => " . $tgl_mulai);
        
        // Mengonversi waktu tiba dan selesai ke format 24 jam dengan zona waktu Jakarta
        $formatted_arrival_time = date('H:i', $arrival_time);
        $formatted_finish_time = date('H:i', $finish_time);
        $formatted_start_time = date('H:i', $tgl_mulai);
    
        if (!is_null($finish_time) && $finish_time < 0) {
            $finish_time = 0; // jika waktu selesai sebelum waktu tiba, set service_time ke 0
        }
        
        $service_time = ($finish_time - $arrival_time) / 60;
        $service_time_neww = ($finish_time - $tgl_mulai) / 60;
    
        // Mengonversi waktu mulai ke format 24 jam dengan zona waktu Jakarta
        $start_time = max($current_time, $arrival_time);
    
        $finish_time = $start_time + $service_time * 60; // waktu selesai dalam detik

        // Menghitung waiting_time sebagai selisih antara tgl_mulai dan arrival_time
        $waiting_time = (strtotime($formatted_start_time) - strtotime($formatted_arrival_time)) / 60; // dalam menit// dalam menit

        // Debug: cek nilai waiting_time
        error_log("waiting_time: " . $waiting_time);

        // Menghitung turnaround_time sebagai penjumlahan waiting_time dan service_time_neww
        $turnaround_time = $waiting_time + $service_time_neww; // dalam menit
    
        $statistik[] = [
            'id' => $row['id_antrian'],
            'no_antrian' => $row['no_antrian'],
            'tgl_antrian' => $row['tgl_antrian'],
            'arrival_time' => $formatted_arrival_time, // Menggunakan format waktu 24 jam
            'start_time' => $formatted_start_time, // Menggunakan format waktu 24 jam
            'service_time' => $service_time_neww,
            'finish_time' => $formatted_finish_time, // Menggunakan format waktu 24 jam
            'waiting_time' => $waiting_time, // dalam menit
            'turnaround_time' => $turnaround_time, // dalam menit
            'tgl_mulai' => $row['tgl_mulai']
        ];
    
        $current_time = $finish_time; // perbarui waktu saat ini untuk antrian berikutnya
    }

    return $statistik;
}

    

    private function calculate_average_times($statistik) {
        // $total_waiting_time = 0;
        $total_turnaround_time = 0;
        $count = count($statistik);

        foreach ($statistik as $item) {
            // $total_waiting_time += $item['waiting_time'];
            $total_turnaround_time += $item['turnaround_time'];
        }

        // $avg_waiting_time = $count > 0 ? $total_waiting_time / $count : 0;
        $avg_turnaround_time = $count > 0 ? $total_turnaround_time / $count : 0;

        return [
            // 'avg_waiting_time' => $this->format_time($avg_waiting_time),
            'avg_turnaround_time' => $this->format_time($avg_turnaround_time)
        ];
    }

    private function format_time($minutes) {
        $hours = floor($minutes / 60);
        $minutes = $minutes % 60;
        $seconds = ($minutes - floor($minutes)) * 60;
        return $hours . ' jam ' . floor($minutes) . ' menit ';
    }    

    private function generateBreadcumbs(){
        $data['breadcumbs'] = array(
                array(
                    'nama'=>'Dashboard',
                    'icon'=>'fa fa-dashboard',
                    'url'=>'admin/dashboard'
                ),
                array(
                    'nama'=>'Admin',
                    'icon'=>'fa fa-users',
                    'url'=>'admin/useradmin'
                ),
            );
        return $data;
    }
}
?>
