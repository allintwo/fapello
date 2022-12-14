<?php
// simple page template
class fapello_mypage{
	public $pagetitle = 'Fapello-Content';
	public $menutitle = 'Add Content';
	public $call = 'display';

	function __construct() {

	}

	public function display()
	{

        if(isset($_POST['urlx']))
        {
            $urlx = $_POST['urlx'];
            $urlx = explode(PHP_EOL,$urlx);
            foreach ($urlx as $url)
            {
                $uuu = trim($url);
                if(strlen($uuu) >15)
                {
                    if(strpos($uuu,'.com')>0)
                    {

                    }
                }
            }
        }


        $html_container = <<<lkjjhgsdkufgsdiufugsduioyfgds
<div id="dashboard_site_health" class="postbox" style="">
<div class="postbox-header">
<h2 class="hndle ui-sortable-handle">Insert by Url <span class="input-group"><input style="text-align: center;width: 100px" class="form-control" id="linklimit" type="text" value="50"><span class="input-group-append"><button id="generatebttn" class="form-control button button-primary" type="button">Get LInks</button></span> </span> </h2>
</div>
<div class="inside">
	<div class="health-check-widget">
<form action="" method="post" class="form">
<div class="form-field">
	 <textarea id="urlgatterid" class="form-control" rows="3"></textarea>  
</div>
</form>
<div class="form-field">
	 <button class="button button-primary" onclick="insert_by_url()">Insert</button>  
</div>
<div style="background-color: white;padding: 5px;">
<ul id="xresults">
	   
</ul> 
</div>
</div>
</div>
</div>
lkjjhgsdkufgsdiufugsduioyfgds;


$home_url = esc_url( home_url() );
$theme_dir = get_template_directory_uri();
        echo <<<sdlfhdskfgdsifgasfgdsaifdsgiufds

 <script src="$theme_dir/assets/js/jquery-3.3.1.min.js"></script>
<script src="$theme_dir/assets/js/js-jquery-3.3.1.min.js"></script>

<div id="dashboard-widgets-wrap">
<div id="dashboard-widgets" class="metabox-holder">
	<div id="postbox-container-1">
	
<div id="loaderx" style="background-color: #61665d;text-align: center;display: none">
<svg style="height: 100px; width: 100px" version="1.1" id="L3" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
  viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
<circle fill="none" stroke="#fff" stroke-width="4" cx="50" cy="50" r="44" style="opacity:0.5;"/>
  <circle fill="#fff" stroke="#e74c3c" stroke-width="3" cx="8" cy="54" r="6" >
    <animateTransform
      attributeName="transform"
      dur="2s"
      type="rotate"
      from="0 50 48"
      to="360 50 52"
      repeatCount="indefinite" />
    
  </circle>
</svg>
	
</div>
	    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
        {$html_container}
	    </div>
	</div>
	
</div>
</div>
<script>
function get_links_fapi()
{
    var btngeneratebttn = document.getElementById('generatebttn');
    var inptbxlimit = document.getElementById('linklimit');
    
    btngeneratebttn.addEventListener('click',function (){
      // alert('you click');
       let xlimmmt = inptbxlimit.value;
       $.ajax({
        url: "https://ilovnudes.com/nontheme/api.php?action=get_indexed_urls&response_type=text&limit="+xlimmmt,
        type: 'GET',
       // dataType: 'json', // added data type
        success: function(res) {
           // console.log(res);
           $('textarea#urlgatterid').val(res);
        }
    });
       
    });
}
get_links_fapi();
</script>



<script>
var total_reqs = 0;
var valid_urls_s = [];
var valid_urls_alldone = 0;
var cussfdlhsdlfhds_url = "{$home_url}/wp-admin/admin-ajax.php";
  
function scraper_const_worker()
{
    let nrulx = '';
   let avliable_task = valid_urls_s.length;
    if(valid_urls_s.length > 0)
        {
            nrulx = valid_urls_s[0];
            valid_urls_s.splice(0,1);
        }else{
        valid_urls_alldone = 1;
        $('#xresults').prepend("<li style='background-color: red;'>"+avliable_task+")All url added Complete["+total_reqs+"]</li>");
        return;
        }
  //  alert(nrulx); scraper_const_worker(); return;
     $.ajax({
                method: "POST",
                url:  cussfdlhsdlfhds_url,
                dataType: 'json',
                data: {action:'fapello_action',url:nrulx,pagename:'insert-by-url'},
                success: function(data){
                  
                    if(data.status === 200)
                        {
                            $('#xresults').prepend("<li style='background-color: greenyellow;'>"+avliable_task+ ") " +nrulx+" -Added</li>");
                        }else if(data.status === 203){
                         $('#xresults').prepend("<li style='background-color: gold'>"+avliable_task+ ") "+nrulx+" - Alredy Added</li>");
                        }else{
                            $('#xresults').prepend("<li style='background-color: #eecfcf;'>"+avliable_task+ ") "+nrulx+" - Not Added</li>");
                        }
                    scraper_const_worker();
                },
                error: function(data){
                     $('#xresults').prepend("<li style='background-color: #d291ff;'>"+avliable_task+ ") "+nrulx+" - Wrong Url</li>");
                    scraper_const_worker();
                }
        }); 
}


function insert_by_url()
{
    
    
    let someurlx = $('#urlgatterid').val();
   
     var separateLines = someurlx.split(/\\r?\\n|\\r|\\n/g);
     valid_urls_s = [];
     total_reqs = 0;
     
     for(i=0;i<separateLines.length;i++)
       {
            // alert(separateLines[i]);
             let nrulx = separateLines[i];
             if(nrulx.length>8)
                 {
                     let mtcs = nrulx.match('.*fapello.com\/([^\/]+)\/$')
                    if(mtcs !== null)
                        {
                          
                              valid_urls_s.push(nrulx);
                        }
                 }
       }
     total_reqs = valid_urls_s.length;
         scraper_const_worker();
}    

jQuery.ajaxSetup({
  beforeSend: function() {
     $('#loaderx').show();
  },
  complete: function(){
     $('#loaderx').hide();
  },
  success: function() {}
});

</script>


sdlfhdskfgdsifgasfgdsaifdsgiufds;

	}




}