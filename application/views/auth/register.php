<content>
	<!-- <div style="margin-top: 100px;"> -->
    <div>
		<div class="container-single-ban">
			<img src="<?= base_url() ?>assets/img/Banner Slide 1.png">
			<div class="scrollregist">
                <!-- <div>Scroll down</div> -->
            </div>
			<script type="text/javascript">
				$('.scrollregist').click(function (e) {
	                $('html,body').animate({scrollTop: $('#form-register').offset().top-70 }, 'slow');
	              });
			</script>
            <style type="text/css">
                /* The animation code */
                @keyframes arrow {
                    0%   {bottom: 1%;}
                    25%  {bottom: 4%;}
                    50%  {bottom: 4%;}
                    100% {bottom: 1%;}
                }

                /* The element to apply the animation to */
                .scrollregist {
                    animation: arrow 3s infinite;
                }
                .scrollregist div{
                    text-align: center;
                    position: absolute;
                    width: 100%;
                    bottom: -20px;
                    font-weight: bold;
                }
            </style>
		</div>

	</div>
	<div class="container">
		<div class="col-md-6 col-md-offset-3 reg-why form">
			<H3>WHY CHOOSING US</H3>
			<p>
				We can easily track the last detail position of your package whether it using Air Way Bill Number or The Order Number from Our E-Commerce Partner. We can easily track the last detail position of your package whether it using Air Way Bill Number or The Order Number from Our E-Commerce Partner. 
			</p>

			<H3 id="form-register" >Delivery Sign Up</H3>
			<span>Complete the form below and our team will immediately contact you</span>
			<form class="form-horizontal" method="post" action="" style="text-align: left; margin-top: 20px;">
                <!-- <div class="col-md-12"> -->
                    <div class="form-group">
                        <label  for="name">&nbsp;Your Name</label>
                        <input  type="text" name="name">
                    </div>
                    <div class="form-group">
                        <label  for="name">&nbsp;Email</label>
                        <input  type="text" name="name">
                    </div>
                    <div class="form-group">
                        <label  for="name">&nbsp;Phone</label>
                        <input  type="text" name="name">	
                    </div>
                    <div class="form-group">
                        <label  for="name">&nbsp;Your Business Name</label>
                        <input  type="text" name="name">
                    </div>
                    <div class="form-group">
                        <label  for="name">&nbsp;Pick up address</label>
                        <input  type="text" name="name">
                    </div>
                    <div class="form-group">
                        <label  for="name">&nbsp;Type of product to shipped</label>
                        <input  type="text" name="name">
                    </div>
                    <div class="form-group">
                        <label  for="name">&nbsp;How much you deliver in a day?</label>
                        <select class="sel-pack">
                        	<option disabled selected>--Select--</option>
                            <option>0 - 100</option>
                            <option>100 - 250</option>
                            <option>250 - 500</option>
                        	<option>More than 500</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label  for="name">&nbsp;You Know JX from</label>
                        <select class="sel-pack">
                        	<option disabled selected>--Select--</option>
                            <option>Social Media</option>
                            <option>Friends & Colleague</option>
                            <option>JD.ID</option>
                            <option>Others</option>
                        </select>
                    </div>
                    <div class="form-group">
                    	<button class="btn btn-alert col-md-3 col-sm-4 col-xs-5 align-left">SEND FORM <img src="<?= base_url() ?>assets/img/arrow.png" height="25" width="25" style="right: 8px; top: 5px; position: absolute;"></button>
                    </div>
                    <h2></h2>
                <!-- </div> -->
            </form>
		</div>
	</div>
</content>