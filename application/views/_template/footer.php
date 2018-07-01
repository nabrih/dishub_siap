   
    <script src="<?= base_url() ?>assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>assets/js/other-js.js"></script>
    <script src="<?= base_url() ?>assets/js/resCarousel.js"></script>

    <script src="<?= base_url() ?>assets/js/owlcarousel/owl.carousel.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $(".owl-carousel.slide-show").owlCarousel({
            // stagePadding: 50,
            loop:true,
            autoplay: true,
            margin:10,
            autoplayTimeout:3000,
            nav:true,
            dots:false,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:1
                },
                1000:{
                    items:1
                }
            },
            navText : ['<div class="nav-slide-left"></div>','<div class="nav-slide-right"></div>']
          });

          $(".owl-carousel.news").owlCarousel({
            // stagePadding: 50,
            loop:false,
            margin:10,
            nav:true,
            dots:false,
            navText : ['<div class="nav-news-left"></div>','<div class="nav-news-right"></div>'],
            touchDrag  : false,
            mouseDrag  : false,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:2
                },
                1000:{
                    items:3
                }
            }
          });
          var newsno=5;
          $(".nav-news-right").click(function(e) {
                $('#news').trigger('add.owl.carousel', ['<div > <img src="<?= base_url() ?>assets/img/news-image.png" style="width: 100%; min-height: 200px;"> <h4>News '+(newsno++)+'</h4> <p>E-commerce (electronic commerce or EC) is the buying and selling of goods and services, or the transmitting of funds or data, over an electronic network, primarily the internet. These business transactions occur either as business-to-business, business-to-consumer, consumer-to-consumer or consumer-to-business.</p> <a href="#" style="color: #D00B0B"> Read more</a> </div>']) 
                .trigger('refresh.owl.carousel');
            });

          $(".owl-carousel.testimoni").owlCarousel({
            // stagePadding: 50,
            loop:true,
            autoplay: true,
            margin:10,
            autoplayTimeout:3000,
            // nav:true,
            dots:true,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:2
                },
                1000:{
                    items:2
                }
            }
          });

        });
    </script>

</body>

</html>