<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyB1tbIAqN0XqcgTR1-FxYoVTVq6Is6lD98&sensor=false">
</script>
<script type="text/javascript">

  var idaddress = "LOC0001";
  var locations = [
      ['1', -6.1535748, 106.7754229, 'Jl. Rosela Blok Ee No.10, Wijaya Kusuma, Grogol petamburan, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11460 ', idaddress],
      ['2', -6.1595352, 106.7294676, 'Jl. Rawa Buaya No.5, Rw. Buaya, Cengkareng, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11740', idaddress],
      ['3', -6.1706228,106.808184, 'Jl. Biak No.3C, Cideng, Gambir, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10150', idaddress],
      ['4', -6.2742483,106.8264158, 'Jl. Timbul 34-35, Pejaten Bar., Ps. Minggu, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12510', idaddress],
      ['5', -6.2498261,106.8335776, 'Jl. Mampang Prapatan 10 No. 4 Rt 001/001 Kel. Tegal Parang Kec. Mampang Prapatan', idaddress],
      ['6', -6.243458,106.900206, 'Jl. Raya Pondok Bambu Asri E. 3/2 Rt. 001 Rw. 03', idaddress],
    ];

  function initialize() {

    var myOptions = {
      center: new google.maps.LatLng(-6.203822,106.8363637),
      zoom: 11,
      mapTypeId: google.maps.MapTypeId.ROADMAP

    };
    var map = new google.maps.Map(document.getElementById("mapscon"),
        myOptions);

    setMarkers(map,locations)

  }

  function setMarkers(map,locations){

    var marker, i

  	for (i = 0; i < locations.length; i++)
  	 {  

    		var loan = locations[i][0]
    		var lat = locations[i][1]
    		var long = locations[i][2]
    		var add =  locations[i][3]
        var idadd =  locations[i][4]

    		latlngset = new google.maps.LatLng(lat, long);

        addMarkerWithTimeout(map, latlngset, i, add)

    	}
  }

  function addMarkerWithTimeout(map, latlngset, i, add) {
        var timeout = (i+1)*500;
        window.setTimeout(function() {

            var marker = new google.maps.Marker({  
                  map: map, 
                  // title: loan , 
                  animation: google.maps.Animation.DROP,
                  position: latlngset, 
                  icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+(i+1)+'|FF0000|000000'
                });

            var content = "<div class='item'><p><b>" + (i+1) + ". </b>&nbsp;&nbsp;" + " Address: " + add + '</p><a href="pickuprequest" class="red"><b>PICK UP REQUEST ></b> </a> </div>';


            var infowindow = new google.maps.InfoWindow();

            google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
                return function() {
                   infowindow.setContent(content);
                   infowindow.open(map,marker);
                   // alert();
                };
            })(marker,content,infowindow)); 

            $('#droppoints').append(content);

        }, timeout);


      }


  </script>
  <style type="text/css">
    #mapscon { height: 500px; display: block; margin-bottom: 20px;}
    .droppoints .item{
      margin-top: 10px;
      padding-bottom: 10px;
      border-bottom: 1px solid #C8C6C6;
    }

  </style>
  <content class="clear">
    <div class="container">
      <div class="col-md-12">
        <div class="col-md-8">
          <div id="mapscon"></div>
        </div>
        <div class="col-md-4">
          <h2 class="red">FIND DROP POINT</h2>
          <div id="droppoints" class="col-md-12 droppoints">
            <div class="track-awb">
              <input type="text" placeholder="Enter ZIP Code / City">
              <button><img src="<?= base_url() ?>assets/img/arrow.png" height="25" width="25"></button>
            </div>
            <!-- <div class="item">
               <p><b>0. </b> E.g : Jl. Rosela Blok Ee No.10, Wijaya Kusuma, Grogol petamburan, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11460 </p>
               <a href="pickuprequest" class="red"><b>PICK UP REQUEST ></b></a>
               <div class="line-div"></div>
            </div> -->
          </div>
          <button class="btncheck cont" >SEE MORE  <img class="right" src="<?= base_url() ?>assets/img/arrow.png" height="25" width="25"></button>
        </div>
      </div>
    </div>
  </content>
  
  <script type="text/javascript">
    initialize();
  </script>