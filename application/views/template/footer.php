<!-- <footer class="main-footer"> -->
    <!-- <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.2.0
    </div> -->
  <!-- </footer> -->
  

<script src="<?= base_url();?>public/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= base_url();?>public/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?= base_url();?>public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="<?= base_url();?>public/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="<?= base_url();?>public/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="<?= base_url();?>public/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?= base_url();?>public/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?= base_url();?>public/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?= base_url();?>public/plugins/moment/moment.min.js"></script>
<script src="<?= base_url();?>public/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?= base_url();?>public/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="<?= base_url();?>public/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?= base_url();?>public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url();?>public/dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url();?>public/dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?= base_url();?>public/dist/js/pages/dashboard.js"></script>




<!-- DataTables  & Plugins -->
<script src="<?= base_url();?>public/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url();?>public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url();?>public/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url();?>public/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url();?>public/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url();?>public/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url();?>public/plugins/jszip/jszip.min.js"></script>
<script src="<?= base_url();?>public/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?= base_url();?>public/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?= base_url();?>public/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url();?>public/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= base_url();?>public/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap core JavaScript-->


    <!-- Core plugin JavaScript-->
    <script src="<?= base_url();?>public/plugins/vendor/jquery/jquery.min.js"></script>

    <script src="<?= base_url();?>public/plugins/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url();?>public/plugins/vendor/jquery-easing/jquery.easing.min.js"></script>


    <script src="<?= base_url();?>vendors/jquery/dist/jquery.min.js"></script>
    <script src="<?= base_url();?>vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="<?= base_url();?>vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?= base_url();?>assets/js/main.js"></script>


    <script src="<?= base_url();?>vendors/chart.js/dist/Chart.bundle.min.js"></script>
    <script src="<?= base_url();?>assets/js/dashboard.js"></script>
    <script src="<?= base_url();?>assets/js/widgets.js"></script>
    <script src="<?= base_url();?>vendors/jqvmap/dist/jquery.vmap.min.js"></script>
    <script src="<?= base_url();?>vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <script src="<?= base_url();?>vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        (function($) {
            "use strict";

            jQuery('#vmap').vectorMap({
                map: 'world_en',
                backgroundColor: null,
                color: '#ffffff',
                hoverOpacity: 0.7,
                selectedColor: '#1de9b6',
                enableZoom: true,
                showTooltip: true,
                values: sample_data,
                scaleColors: ['#1de9b6', '#03a9f5'],
                normalizeFunction: 'polynomial'
            });
        })(jQuery);
 

function viewpass() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}


    $(document).ready(function () {
        // Handle search button click
        $('#searchButton').click(function () {
            var keyword = $('#searchKeyword').val();

            // Make AJAX request
            $.ajax({
                url: '<?= base_url('index.php/Userscontroller/search'); ?>',
                type: 'GET',
                data: { keyword: keyword },
                success: function (data) {
    console.log('Success:', data);
},
error: function (xhr, status, error) {
    console.log('Error:', xhr.responseText);
}
            });
        });
    });

    </script>
</body>
</html>
