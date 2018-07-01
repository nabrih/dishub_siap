    </div>
    <!-- /#wrapper -->

<!--     <script src="<?php echo base_url(); ?>assets/js/jquery-1.11.0.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.id.min.js" charset="UTF-8"></script>
    <script src="<?php echo base_url(); ?>assets/js/dashboard.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/metisMenu/metisMenu.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/dataTables/dataTables.bootstrap.js"></script>
 -->
    <!-- Pengaturan data pada tabel -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dataKendaraan').DataTable({
                //"order": [[0, "desc"]]      //secara default menampilkan data dengan pengurutan terbalik (descending)
            });
            $('#dataKendaraanUji').DataTable({

                aLengthMenu: [
                    [25, 50, 100, 200, -1],
                    [25, 50, 100, 200, "All"]
                ],
                //"order": [[0, "desc"]]      //secara default menampilkan data dengan pengurutan terbalik (descending)
            });
            $('#list_spt').DataTable({
                //"order": [[0, "desc"]]      //secara default menampilkan data dengan pengurutan terbalik (descending)
            });
            $('#list_dimensi').DataTable({
                "scrollX": true
                //"order": [[0, "desc"]]      //secara default menampilkan data dengan pengurutan terbalik (descending)
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            $(".datepicker").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true
            });
            $(".datepicker").on("changeDate", function(event){
                $(".datepicker").val(
                        $(".datepicker").datepicker('getFormattedDate')
                    )
            });
        });

        function loadByDate() {
            window.location = "<?php echo site_url().'/kegiatan_uji/tanggal/' ?>" + document.getElementById('datepicker').value;
        }
        function loadByDateBaru() {
            window.location = "<?php echo site_url().'/kegiatan_uji_baru/tanggal/' ?>" + document.getElementById('datepicker').value;
        }
        
        function loadIKM() {
            window.location = "<?php echo site_url().'/ikm/tanggal/' ?>" + document.getElementById('datepicker').value;
        }
        function printIKM() {
            window.location = "<?php echo site_url().'/ikm/print_pdf/' ?>" + document.getElementById('datepicker').value;
        }
    </script>

    <script type="text/javascript">
    function activeInput(argument) {
        $(document).ready(function () {
            $("#"+argument).keyup(function () {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url()?>/spt/caripetugas",
                    data: {
                        keyword: $("#"+argument).val()
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data.length > 0) {
                            $('#DropdownNama'+argument).empty();
                            $('#'+argument).attr("data-toggle", "dropdown");
                            $('#DropdownNama'+argument).dropdown('toggle');
                        }
                        else if (data.length == 0) {
                            $('#'+argument).attr("data-toggle", "");
                        }
                        $.each(data, function (key,value) {
                            if (data.length >= 0)
                                $('#DropdownNama'+argument).append('<li role="presentation" ><a role="menuitem dropdownnameli" class="dropdownlivalue" style="cursor:pointer" nl="'+value['nama_long']+'" ns="'+value['nama_short']+'" nip="'+value['nip']+'" nrk="'+value['reg_id']+'" >' + value['nama_long'] + '</a></li>');
                        });
                    }
                });
            });
            $('ul.txtnama'+argument).on('click', 'li a', function () {
                $('#'+argument).val($(this).text());
                $('#nl'+argument).val($(this).attr('nl'));
                $('#ns'+argument).val($(this).attr('ns'));
                $('#nip'+argument).val($(this).attr('nip'));
                $('#nrk'+argument).val($(this).attr('nrk'));
            });
        });
    }
    </script>

     <script type="text/javascript"> 

        function showLoading(show) {
          if (show) {
            $('.preloader').fadeIn();
          }else
          $('.preloader').fadeOut();
        }

        // custom modal
        function modal(show) {
          if (show) {
            $('.overlay').fadeIn();
          }else
          $('.overlay').fadeOut();
        }

        function close_dialog() {
          modal(0);
        }
        function open_dialog() {
          modal(1);
        }
        // custom modal end

        // load url to container
        function load_to(url, container, callback){
          showLoading(1);
          $(container).load(url, function( response, status, xhr ) {
            var msg;
            if ( status == "error" ) {
              msg = "<div class='col-md-12' style='text-align: center; color: red; min-height: 70px;  padding-top: 25px;'>Can not load the resource</div> ";
              $(this).html(msg);
            }
            
            callback();
            showLoading(0);
          });
        }

        // get method
        function get(url, check, callback) {
            showLoading(1);
            $.get(url, function(data) {
                if (data['status']==check) {
                    if(jQuery.isFunction(callback)){
                      callback();
                    }
                }else{
                  showLoading(0);
                  console.log(data);
                  alert(data['status']+':'+data['error']);
                }
            }).fail(function() {
                showLoading(0);
                alert( "error" );
            });
        }

        // post method
        function post(url, datasend, check, callback) {
          showLoading(1);
          var ap = $.post(url, datasend, function(data) {
                var json = JSON.stringify(data);
                if (data['status']=='success'){
                  callback();
                }else{
                  console.log(data);
                  alert(data['status']);
                }
                showLoading(0);
              });

          ap.fail(function(data) {
            alert( "Error: Can not connect to resource");
            console.log(data);
            showLoading(0);
          });
        }

        function load_menu(url){
          load_to(url, "#content", function() {
              
          });
        }

        function load_modal(url){
          load_to(url, "#content-dialog", function() {
              open_dialog();
          });
        }

        function upload(url, check, btn_exec, file_class, error_container, data_send) {                      
            $(btn_exec).prop('disabled', true);
            // AJAX upload
            $(file_class).upload(url, 
              data_send,
              function(data){
                if (data) {
                  $(error_container).html(data['status']);

                  if (data['status']==check){
                        setTimeout(function () {
                              close_dialog();
                              load_menu('<?= base_url() ?>jxpanel/banner');
                        }, 3000);
                  }else{
                        console.log(data);
                        $(btn_exec).prop('disabled', false);
                  }
                }else{
                  $(error_container).html('Can not load the resource');
                  $(btn_exec).prop('disabled', false);
                }
              }, 
              // ON PROGRESS BAR
              function(prog, value){
                $('#progress').css('display', 'block');
                var per = value+'%';
                $('#percent2').html(value);
                $('#bar').css('width', per);
            }); 
          }
    </script>
</body>

</html>