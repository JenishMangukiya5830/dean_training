<?php

namespace layout;

Class Footer implements Layout
{

    function getFacebookLikes()
    {
        return 1744;
        $cache_file = "cache/facebook";
        $number = 0;
        if (file_exists($cache_file) && (filemtime($cache_file) > (time() - 60 * 1440))) {
            // Cache file is less than five minutes old.
            // Don't bother refreshing, just use the file as-is.
            $number = file_get_contents($cache_file);
        } else {
            // Our cache is out-of-date, so load the data from our remote server,
            // and also save it over our cache for next time.
            $url = "http://api.facebook.com/restserver.php?method=facebook.fql.query&query=SELECT%20fan_count%20FROM%20page%20WHERE%20page_id=247354788671731";
            $xml = simplexml_load_file($url);
            $number = $xml->page->fan_count;
            file_put_contents($cache_file, $number, LOCK_EX);
        }
        return $number;
    }

    function render(Array $option)
    {
        $phone = '';
        if (isset($option['phone'])) {
            $phone = "<p><i class='fa fa-phone'></i> {$option['phone']}</p>";
        }

        $data = "</div><div class='container-fluid footer'>    
    <div class='row'>
        <div class='col-md-4'>
            <ul class='unstyled'>
           
                <li>London<li>
                 <li><a class='btn btn-red btn-full-100' href='tel:02089657818'><i class='fa fa-phone'></i> Call Dean Training</a></h4></li>
                <li>
                    <h4>Address</h4>
                    <p><i class='fa fa-home'></i> Rays House, North Circular Road, NW10 7XP, London</p>
                    <p><i class='fa fa-clock-o'></i> 9:00-18:00 MON-SAT</p>
                    {$phone}
                    <p><i class='fa fa-phone'></i> 08000 525 444 (Free From landline)</p>
                    <p><i class='fa fa-phone'></i> 0208 965 7818</p>
                    <p><i class='fa fa-mobile'></i> 07961 765 555 (Out of hours)</p>
                    <p><i class='fa fa-envelope-o'></i> sales@deantraining.co.uk</p>
                </li>
                <li>
                    <h4>Map</h4>
                    <img src='" . PUBLIC_BASE . "/img/map.jpg' />
                    <h4>Directions</h4>
                    <div class='text-center'>
                        <a href='https://www.google.co.uk/maps/preview#!data=!1m4!1m3!1d1926!2d-0.2877112!3d51.5360554!4m36!3m16!1m0!1m5!1sRays+House%2C+N+Circular+Rd%2C+London+NW10+7XP!2s0x48761221e4b27701%3A0x8fde9d52dc21007d!3m2!3d51.5360554!4d-0.2877112!3m8!1m3!1d30809!2d-0.254258!3d51.5449983!3m2!1i1366!2i596!4f13.1!5m16!2m15!1m14!1s0x48761221e4b27701%3A0x8fde9d52dc21007d!2sRays+House+North+Circular+Road+London+NW10%C2%A07XP!3m8!1m3!1d30809!2d-0.254258!3d51.5449983!3m2!1i1366!2i596!4f13.1!4m2!3d51.5360554!4d-0.2877112!7m1!3b1&amp;fid=0' target='_blank'>
                            <img src='" . PUBLIC_BASE . "/img/car1.png' height='50px' width='50px'>
                        </a>
                        <a href='https://www.google.co.uk/maps/preview#!data=!1m4!1m3!1d3852!2d-0.2874752!3d51.5363624!4m20!3m17!1m0!1m5!1sRays+House%2C+N+Circular+Rd%2C+London+NW10+7XP!2s0x48761221e4b27701%3A0x8fde9d52dc21007d!3m2!3d51.5360554!4d-0.2877112!2e3!3m8!1m3!1d1926!2d-0.2877112!3d51.5360554!3m2!1i1366!2i596!4f13.1!7m1!3b1&amp;fid=0' target='_blank'>
                            <img src='" . PUBLIC_BASE . "/img/train1.png' height='50px' width='50px'>
                        </a>
                        <a href='https://www.google.co.uk/maps/preview#!data=!1m4!1m3!1d3852!2d-0.2874752!3d51.5363624!4m20!3m17!1m0!1m5!1sRays+House%2C+N+Circular+Rd%2C+London+NW10+7XP!2s0x48761221e4b27701%3A0x8fde9d52dc21007d!3m2!3d51.5360554!4d-0.2877112!2e2!3m8!1m3!1d3852!2d-0.2874752!3d51.5363624!3m2!1i1366!2i596!4f13.1!7m1!3b1&amp;fid=0' target='_blank'>
                            <img src='" . PUBLIC_BASE . "/img/walk1.png' height='50px' width='50px'>
                        </a>
                    </div>
                </li>                
            </ul>
        </div>
        <div class='col-md-4'>
        <ul class='unstyled'>
            <li>Translate<li>
            <p>
            <div id='google_translate_element'></div><script type='text/javascript'>
            function googleTranslateElementInit() {
              new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
            }
            </script><script type='text/javascript' src='//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit'></script>
            </p>
            <img src='" . PUBLIC_BASE . "/img/flags.png' style='width: 100%;'/>
            </ul>
            <ul class='unstyled'>
                <li>Dean Training<li>
                <li>
                    <h4>Since</h4>
                    <p><i class='fa fa-clock-o fa-3x'></i> 2010</p>                    
                </li>
                <li>
                    <h4>Facebook</h4>
                    <p><i class='fa fa-facebook-square fa-3x'></i> " . $this->getFacebookLikes() . " Like</p>                    
                </li>
                 <li>
                    <h4>Facebook</h4>
                    <p><i class='fa fa-male fa-3x'></i> 12173 Clients</p>                    
                </li>
                <li><br/><a class='btn btn-red btn-full-100' href='http://isecuredirect.com/partnerportal'><i class='fa fa-lock'></i> Partner Portal</a></h4></li>
                <li><br/><a class='btn btn-red btn-full-100' href='/request-certificate/'><i class='fa fa-lock'></i> Request Certificate</a></h4></li>
                <li><br/><a class='btn btn-red btn-full-100' target='_blank' href='https://www.google.com/search?safe=off&hl=en-GB&gl=uk&ei=uadEXPmoMd6h1fAPm-KOwAY&q=dean+training&oq=dean+training&gs_l=psy-ab.3..0j0i20i263j0l7.12129.13310..13408...0.0..1.136.965.12j1......0....1..gws-wiz.......0i71j35i39j0i67j0i131j0i10.T3rbBi_94xU#lrd=0x48761221e3591197:0xebce883cc983ad93,3,,,'><i class='fa fa-heart'></i> Leave Feedback</a></h4></li>

            </ul>    
            
            </div>
                        
        
        <div class='col-md-4'>
            <ul class='unstyled'>
                <li>Connect Us<li>                
                <li>                    
                    <div class='text-left'>
                        <a href='https://www.facebook.com/pages/DEAN-Training/247354788671731' target='_blank'><i class='fa fa-facebook-square fa-5x'></i></a>                    
                        &nbsp;&nbsp;<a href='https://twitter.com/Dean_Training/' target='_blank'><i class='fa fa-twitter-square fa-5x'></i></a>
                        &nbsp;&nbsp;<a href='https://www.instagram.com/deantraining/' target='_blank'><i class='fa fa-instagram fa-5x'></i></a>
                    </div>
                </li>
                <li><h4>We Support Cause</h4><img src='" . PUBLIC_BASE . "/img/support_cause.jpg' /></li>                   
            </ul>
        </div>        	            				
    </div>
    <div class='row'>
        <div class='col-md-4'>
            <a href='" . PUBLIC_BASE . "/terms/'>Terms of Service</a> | 
            <a href='" . PUBLIC_BASE . "/privacy/'>Privacy</a> |
            <a href='" . PUBLIC_BASE . "/refund/'>Refund policy</a>
        </div>
        <div class='col-md-8'>
            <p class='muted text-right'>©" . Date('Y') . " Dean Training. All rights reserved</p>
        </div>
    </div>
    
      <div id='ouibounce-modal'>
      <div class='underlay'></div>
      <div class='modal'>
        <div class='modal-title'>
          <h3>Limited Time Offer</h3>
        </div>

        <div class='modal-body'>           
          <p>Dean Training is offering site wide discount of <span style='font-size: 30px;'>10%</span></p>
          <p>Hurry up don't miss out the discounted pricing</p>          
          <div id='popup-timer'><span id='the-timer'></span></div>
          <center>
            <a href='http://deantraining.co.uk/book-now/' class='btn red'>Book Now</a>   
          </center>                
        </div>

        <div class='modal-footer'>
          <p onclick='$(\"#ouibounce-modal\").hide();'>No thanks</p>
        </div>
      </div>
    </div>

</div><script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-46656367-1', 'auto');
  ga('send', 'pageview');

</script>

<!-- ClickDesk Live Chat Service for websites -->
<script type='text/javascript'>
var _glc =_glc || []; _glc.push('all_ag9zfmNsaWNrZGVza2NoYXRyDgsSBXVzZXJzGJSA5hMM');
var glcpath = (('https:' == document.location.protocol) ? 'https://my.clickdesk.com/clickdesk-ui/browser/' : 
'http://my.clickdesk.com/clickdesk-ui/browser/');
var glcp = (('https:' == document.location.protocol) ? 'https://' : 'http://');
var glcspt = document.createElement('script'); glcspt.type = 'text/javascript'; 
glcspt.async = true; glcspt.src = glcpath + 'livechat-new.js';
var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(glcspt, s);
</script>
<!-- End of ClickDesk -->

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '468893630971601'); 
fbq('track', 'PageView');
</script>
<noscript>
<img height='1' width='1' src='https://www.facebook.com/tr?id=468893630971601&ev=PageView&noscript=1'/>
</noscript>
<!-- End Facebook Pixel Code -->
";
        return $data;
    }
}
