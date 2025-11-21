
(function ($) {
  'use strict';

  var imJs = {
      m: function (e) {
          imJs.d();
          imJs.methods();
      },
      d: function (e) {
          this._window = $(window),
          this._document = $(document),
          this._body = $('body'),
          this._html = $('html')
      },
      methods: function (e) {
          imJs.swiperActivation();
          imJs.backToTopInit();
          imJs.isotopActivation();
          imJs.stickyHeader();
          imJs.counterUp();
          imJs.salActive();
          imJs.cartActive();
          imJs.mobilemenu();
          imJs.searchClick();
          imJs.uploadimg();
          imJs.vedioActivation();
          imJs.cursorAnimate();
          imJs.preloader();
      },

      swiperActivation: function (){
        $(document).on().ready(function() {
            let swiperContainer = $('.swiper-container');
            if (swiperContainer.length) {
            var swiper = new Swiper(".swiper-container", {
              slidesPerView: 3,
              spaceBetween: 50,
              slidesPerGroup: 1,
              loop: true,
              // loopFillGroupWithBlank: true,
              centeredSlides: true,
              breakpoints: {
                1500: {
                  slidesPerView: 3,
                },
                1199: {
                  slidesPerView: 2,
                },
                991: {
                  slidesPerView: 2,
                },
                767: {
                  slidesPerView: 1,
                },
                575: {
                  slidesPerView: 1,
                },
                0: {
                  slidesPerView: 1,
                }
              },
              // autoplay: {
              //   delay: 2500,
              //   disableOnInteraction: true
              // },
              navigation: {
                nextEl: ".testimonials-slider-next-btn",
                prevEl: ".testimonials-slider-prev-btn"
              }
            });
          }
        });
        $(document).on().ready(function() {
          var swiper = new Swiper(".mySwipers", {
            effect: "cube",
            grabCursor: true,
            speed: 1500,
            loop: true,
            loopedSlides: 10,
            autoHeight: true,
            shortSwipes: false,
            longSwipes: false,
            cubeEffect: {
              shadow: true,
              slideShadows: true,
              shadowOffset: 20,
              shadowScale: 0.94,
            },
            autoplay: {
              delay: 500,
            },
          });
        });
        $(document).on().ready(function() {
          var swiper = new Swiper(".LiveBidding", {
            slidesPerView: 5,
            spaceBetween: 0,
            slidesPerGroup: 1,
            loop: true,
            loopFillGroupWithBlank: true,
            pagination: {
              el: ".swiper-pagination",
              clickable: true,
            },
            navigation: {
              nextEl: ".swiper-button-next",
              prevEl: ".swiper-button-prev",
            },
            breakpoints: {
              1860: {
                slidesPerView: 5,
                spaceBetween: 10,
              },
              1400: {
                slidesPerView: 4.5,
                spaceBetween: 0,
              },
              1200: {
                slidesPerView: 3.5,
                spaceBetween: 0,
              },
              992: {
                slidesPerView: 2.9,
                spaceBetween: 0,
              },
              768: {
                slidesPerView: 2,
                spaceBetween: 0,
              },  
              494: {
                slidesPerView: 1,
                spaceBetween: 0,
              },
              360: {
                slidesPerView: 1,
                spaceBetween: 0,
              }
            }
          });
        });
        var swiper = new Swiper(".mySwiperfluid", {
          slidesPerView: 5.5,
          spaceBetween: 30,
          slidesPerGroup: 1,
          centeredSlides: true,
          loop: true,
          loopFillGroupWithBlank: true,
          pagination: {
            el: ".swiper-pagination",
            clickable: true,
          },
          navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
          },
          breakpoints: {
            1860: {
              slidesPerView: 4,
              spaceBetween: 30,
            },
            1400: {
              slidesPerView: 4,
              spaceBetween: 30,
            },
            1200: {
              slidesPerView: 3,
              spaceBetween: 30,
            },
            992: {
              slidesPerView: 2,
              spaceBetween: 30,
              centeredSlides: false,
            },
            768: {
              slidesPerView: 1,
              spaceBetween: 30,
            },  
            596: {
              slidesPerView: 1,
              spaceBetween: 30,
            },
            450: {
              slidesPerView: 1,
              spaceBetween: 30,
            },
            360: {
              slidesPerView: 1,
              spaceBetween: 30,
            }
          }
        });
        jQuery(document).ready(function($) {
        // This finds the numbers and makes them count up
        // when you scroll to them.
        $('.counter-value').counterUp({
            delay: 10,  // The delay in ms per update
            time: 1500  // The total time to count
        });
    });
        var swiper = new Swiper(".mySwiperRelated", {
          slidesPerView: 3,
          spaceBetween: 30,
          slidesPerGroup: 1,
          loop: true,
          loopFillGroupWithBlank: true,
          pagination: {
            el: ".swiper-pagination",
            clickable: true,
          },
          navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
          },
          breakpoints: {
            992: {
              slidesPerView: 2,
              spaceBetween: 20,
            },
            768: {
              slidesPerView: 2,
              spaceBetween: 20,
            },
            560:{
              slidesPerView: 1.3,
              spaceBetween: 20,
              navigation:false,
              pagination:false,
            },
            366: {
              slidesPerView: 1.3,
              spaceBetween: 20,
            },
          }
        });
      },
      backToTopInit: function (){
        $(document).on().ready(function(){"use strict";
		
        var progressPath = document.querySelector('.progress-wrap path');
        var pathLength = progressPath.getTotalLength();
        progressPath.style.transition = progressPath.style.WebkitTransition = 'none';
        progressPath.style.strokeDasharray = pathLength + ' ' + pathLength;
        progressPath.style.strokeDashoffset = pathLength;
        progressPath.getBoundingClientRect();
        progressPath.style.transition = progressPath.style.WebkitTransition = 'stroke-dashoffset 10ms linear';		
        var updateProgress = function () {
          var scroll = $(window).scrollTop();
          var height = $(document).height() - $(window).height();
          var progress = pathLength - (scroll * pathLength / height);
          progressPath.style.strokeDashoffset = progress;
        }
        updateProgress();
        $(window).scroll(updateProgress);	
        var offset = 50;
        var duration = 550;
        jQuery(window).on('scroll', function() {
          if (jQuery(this).scrollTop() > offset) {
            jQuery('.progress-wrap').addClass('active-progress');
          } else {
            jQuery('.progress-wrap').removeClass('active-progress');
          }
        });				
        jQuery('.progress-wrap').on('click', function(event) {
          event.preventDefault();
          jQuery('html, body').animate({scrollTop: 0}, duration);
          return false;
        })
        
        
      });

      },
      isotopActivation: function (){
        $(window).on("load", function() {
          // init Isotope
          var $grid = $('.grid').isotope({
            itemSelector: '.element-item',
            layoutMode: 'fitRows'
          });
          // filter functions
          var filterFns = {
            // show if number is greater than 50
            numberGreaterThan50: function() {
              var number = $(this).find('.number').text();
              return parseInt( number, 10 ) > 50;
            },
            // show if name ends with -ium
            ium: function() {
              var name = $(this).find('.name').text();
              return name.match( /ium$/ );
            }
          };
          // bind filter button click
          $('.filters-button-group').on( 'click', 'button', function() {
            var filterValue = $( this ).attr('data-filter');
            // use filterFn if matches value
            filterValue = filterFns[ filterValue ] || filterValue;
            $grid.isotope({ filter: filterValue });
          });
          // change is-checked class on buttons
          $('.button-group').each( function( i, buttonGroup ) {
            var $buttonGroup = $( buttonGroup );
            $buttonGroup.on( 'click', 'button', function() {
              $buttonGroup.find('.is-checked').removeClass('is-checked');
              $( this ).addClass('is-checked');
            });
          });

       });
      },
      stickyHeader: function (e){
        $(window).on().scroll(function () {
            if ($(this).scrollTop() > 150) {
                $('.header--sticky').addClass('sticky')
            } else {
                $('.header--sticky').removeClass('sticky')
            }
        })
      },
      counterUp: function (){
        $(document).ready(function(){
          $('.counter').counterUp({
            delay: 10,
            time: 1000
          });
          $('.counter').addClass('animated fadeInDownBig');
          $('h3').addClass('animated fadeIn');
        });
      },
      salActive: function (){
        sal({
            threshold: 0.1,
            once: true,
        });
      },
      cartActive: function(){
        $(function() {
          $(".button").on("click", function() {
            var $button = $(this);
            var $parent = $button.parent(); 
            var oldValue = $parent.find('.input').val();
         
            if ($button.text() == "+") {
               var newVal = parseFloat(oldValue) + 1;
             } else {
                // Don't allow decrementing below zero
               if (oldValue > 1) {
                 var newVal = parseFloat(oldValue) - 1;
                 } else {
                 newVal = 1;
               }
               }
             $parent.find('a.add-to-cart').attr('data-quantity', newVal);
             $parent.find('.input').val(newVal);
          });
         });

          document.querySelectorAll(".remove").forEach(removebtn => removebtn.addEventListener("click", removeProduct));
          function removeProduct(e) {
            e.preventDefault()
            this.parentElement.parentElement.removeChild(this.parentElement);
          }
      },
      mobilemenu: function(){
        $('.hamberger-button').on('click', function (e) {
          $('.popup-mobile-menu').addClass('active');
        });

        $('.close-menu').on('click', function (e) {
            $('.popup-mobile-menu').removeClass('active');
            $('.popup-mobile-menu .main-menu .rts-dropdown > a, .popup-mobile-menu .main-menu .with-megamenu > a').siblings('.submenu, .rn-megamenu').removeClass('active').slideUp('400');
            $('.popup-mobile-menu .main-menu .rts-dropdown > a, .popup-mobile-menu .main-menu .with-megamenu > a').removeClass('open')
        });

        $('.popup-mobile-menu .main-menu .rts-dropdown > a, .popup-mobile-menu .main-menu .with-megamenu > a').on('click', function (e) {
            e.preventDefault();
            $(this).siblings('.submenu, .rn-megamenu').toggleClass('active').slideToggle('400');
            $(this).toggleClass('open')
        })

        $('.popup-mobile-menu').on('click', function (e) {
            e.target === this && $('.popup-mobile-menu').removeClass('active') && $('.popup-mobile-menu .main-menu .rts-dropdown > a, .popup-mobile-menu .main-menu .with-megamenu > a').siblings('.submenu, .rn-megamenu').removeClass('active').slideUp('400') && $('.popup-mobile-menu .main-menu .rts-dropdown > a, .popup-mobile-menu .main-menu .with-megamenu > a').removeClass('open');
        });

        $('.one-page-vavigation-popup .main-menu li > a').on('click' , function (e) {
            e.preventDefault();
            $('.popup-mobile-menu').removeClass('active');
            $('.popup-mobile-menu .main-menu li > a').siblings('.submenu').removeClass('active');
        })
      },
      searchClick:function (e){        
        $(window).on("load", function(){
          var screenWidth = imJs._window.width();
          if (screenWidth < 992) {
            $('.search-mobile-icon').on('click', function (e) {
                e.preventDefault();
                $(this).toggleClass('open').siblings('.large-mobile-blog-search').toggleClass('active');
            })
          }
          //Connect wallet
          if (screenWidth < 780){
            document.getElementById("connect-wallet").innerHTML = "Wallet";
          }
        });
      },
      uploadimg: function name(){
        $("#createfileImage").click(function (e) {
          $("#createinputfile").click();
      });
      function rtsPreview() {
          const [file2] = createinputfile.files
          if (file2) {
              createfileImage.src = URL.createObjectURL(file2)
          }
      }
      $("#createinputfile").change(function () {
          rtsPreview(this);
      });
      },
      vedioActivation: function (e){
        $('#play-video, .play-video').on('click', function (e) {
            e.preventDefault();
            $('#video-overlay, .video-overlay').addClass('open');
            $("#video-overlay, .video-overlay").append('<iframe width="560" height="315" src="https://www.youtube.com/embed/6stlCkUDG_s" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        });

        $('.video-overlay, .video-overlay-close').on('click', function (e) {
            e.preventDefault();
            close_video();
        });

        $(document).keyup(function (e) {
            if (e.keyCode === 27) {
                close_video();
            }
        });

        function close_video() {
            $('.video-overlay.open').removeClass('open').find('iframe').remove();
        };
      },
      cursorAnimate: function (){
        var myCursor = $('.mouse-cursor');
        if (myCursor.length) {
            if ($('body')) {
                const e = document.querySelector('.cursor-inner'),
                    t = document.querySelector('.cursor-outer');
                let n, i = 0,
                    o = !1;
                window.onmousemove = function (s) {
                    o || (t.style.transform = "translate(" + s.clientX + "px, " + s.clientY + "px)"), e.style.transform = "translate(" + s.clientX + "px, " + s.clientY + "px)", n = s.clientY, i = s.clientX
                }, $('body').on("mouseenter", "a, .cursor-pointer", function () {
                    e.classList.add('cursor-hover'), t.classList.add('cursor-hover')
                }), $('body').on("mouseleave", "a, .cursor-pointer", function () {
                    $(this).is("a") && $(this).closest(".cursor-pointer").length || (e.classList.remove('cursor-hover'), t.classList.remove('cursor-hover'))
                }), e.style.visibility = "visible", t.style.visibility = "visible"
            }
        }
      },
      preloader: function (){
        $(document).on().ready(function() {
          var counter = 0;
          function updateCounter(){
            if(counter == 101){
              clearInterval(foo);
              $('.loadingpage').addClass("pageisloaded");
            }
            else{
              $('.counter h1').html(counter);
              counter++;
            }
          }
          
          var foo = setInterval(updateCounter , 15);
          
        });
        
      },
      
  }

  imJs.m();
  

})(jQuery, window)











