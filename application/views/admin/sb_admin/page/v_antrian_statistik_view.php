<!DOCTYPE html>
<html>
<head>
    <title>Statistik Antrian</title>
    <!-- Load CSS for DataTables and Buttons -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
    <!-- Load jQuery and DataTables JS -->
    <script src="//code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
</head>
<body>
    <form method="get" action="" style="margin-bottom:30px">
        <label for="tanggal">Pilih Tanggal:</label>
        <input type="date" id="tanggal" name="tanggal" value="<?php echo isset($_GET['tanggal']) ? $_GET['tanggal'] : ''; ?>">
        <select name="get_poli" id="get_poli">
            <?php foreach ($get_poli as $poli): ?>
                <option value="<?php echo $poli['id_poli']; ?>"><?php echo $poli['nama_poli']; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filter</button>
    </form>
    <table id="antrian_statistik" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>No Antrian</th>
                <th>Tanggal Antrian</th>
                <th>Waktu Tiba (Arrival Time)</th>
                <th>Lama Proses (Burst Time)</th>
                <th>Mulai Eksekusi (Start Time)</th>
                <th>Waktu Selesai (Completion Time)</th>
                <th>Waktu Tunggu (Waiting Time)</th>
                <th>TA (Turn Around Time)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($antrian_statistik as $statistik): ?>
            <tr>
                <td><?php echo $statistik['id']; ?></td>
                <td><?php echo $statistik['no_antrian']; ?></td>
                <td><?php echo $statistik['tgl_antrian']; ?></td>
                <td><?php echo $statistik['arrival_time']; ?></td>
                <td><?php echo round($statistik['service_time']); ?></td>
                <td><?php echo isset($statistik['tgl_mulai']) ? date('H:i', strtotime($statistik['tgl_mulai'])) : '-'; ?></td>
                <td><?php echo $statistik['finish_time']; ?></td>
                <td>
                <?php
                    echo round($statistik['waiting_time']);
                    ?>
                </td>
                <td>
                <?php
                    $turnaround_time = $statistik['turnaround_time'];
                    $turnaround_time_numeric = (float) $turnaround_time;
                    $turnaround_time_rounded = round($turnaround_time_numeric);
                    echo number_format($turnaround_time_rounded, 0);
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="9" style="text-align:center">Rata-rata Turn Around Time: <?php echo $avg_turnaround_time; ?></th>
            </tr>
        </tfoot>
    </table>
    <script>
        $(document).ready(function() {
            $('#antrian_statistik').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    'pdfHtml5',
                    'print'
                ],
                order: [[1, 'asc']]
            });
        });
    </script>
</body>
</html>
