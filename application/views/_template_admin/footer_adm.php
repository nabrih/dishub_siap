             
            
        </div>
        <!-- End Page wrapper  -->

        <!-- footer -->
        <footer class="footer"> © 2018 <a href="#" target="">Link</a></footer>
        <!-- End footer -->
        
    </div>
    <!-- End Wrapper -->
    
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?= base_url() ?>assets/adm/js/lib/bootstrap/js/popper.min.js"></script>
    <script src="<?= base_url() ?>assets/adm/js/lib/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="<?= base_url() ?>assets/adm/js/jquery.slimscroll.js"></script>
    <!--Menu sidebar -->
    <script src="<?= base_url() ?>assets/adm/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="<?= base_url() ?>assets/adm/js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>

    <script src="<?= base_url() ?>assets/adm/js/lib/weather/jquery.simpleWeather.min.js"></script>
    <script src="<?= base_url() ?>assets/adm/js/lib/weather/weather-init.js"></script>
    <script src="<?= base_url() ?>assets/adm/js/lib/owl-carousel/owl.carousel.min.js"></script>
    <script src="<?= base_url() ?>assets/adm/js/lib/owl-carousel/owl.carousel-init.js"></script>


    <script src="<?= base_url() ?>assets/adm/js/lib/sweetalert/sweetalert.min.js"></script>

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- UPLOAD by Ajax XHR2 -->
    <script src="<?= base_url() ?>assets/js/XHR2.js"></script>


    <!--Custom JavaScript -->
    <script src="<?= base_url() ?>assets/adm/js/custom.min.js"></script>

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