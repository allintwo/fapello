<?php
// simple page template
class fapello_main{
	public $pagetitle = 'fapello-Dashboard';
	public $menutitle = 'Fapello';
	public $call = 'display';
	public function display()
	{


        global $HTTP;

        $locations_list = wp_list_categories( array(
            'taxonomy' => 'Model',
            'orderby' => 'name',
            'show_count' => 0,
            'pad_counts' => 0,
            'hierarchical' => 1,
            'echo' => 0,
            'title_li' => 'Models'
        ) );

        $models_list = '';
// Make sure there are terms with articles
//        if ( $locations_list )
//            $models_list =  '<ul class="locations-list">' . $locations_list . '</ul>';



$model_edit_link = <<<dsflkhfdskjfghdofdfhoisgf
<div>
<a href="{$HTTP}/wp-admin/edit-tags.php?taxonomy=Model" title="wow">Add / Edit Model Info</a>
<p style="font-style: italic">If you wanted to edit Model Info - social links (twitter,instagram,onlyfans),custom image.</p>
</div>
dsflkhfdskjfghdofdfhoisgf;

$models_list = $this->html_container('Model list',$model_edit_link);
$cronjobs_links = <<<sohfoiugerhrogtheorhksdgcviusd
<div><a href="{$HTTP}/cronjobs">Automatic update</a>
<p style="font-style: italic">
Why you visit. Or visited by Cronjobs it will automaticaly Update likes and follower count.
<b>run every hour </b></p>
</div>
<div>
<a href="{$HTTP}/insert-by-url">Automatic Scraper</a>
<p style="font-style: italic">
Why you visit. Or visited by Cronjobs it will automaticaly Add content.<b> run 6-12 hour</b>
</p>
</div>

sohfoiugerhrogtheorhksdgcviusd;


$cronjobs = $this->html_container('Setup cronjobs',$cronjobs_links);



echo <<<sdfdslfhdgdfhgord



<div id="dashboard-widgets-wrap">
<div id="dashboard-widgets" class="metabox-holder">
	
	<div id="postbox-container-1">
	    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
	    {$models_list}
	    </div>
	</div>
	<div id="postbox-container-1">
	    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
	    {$cronjobs}
	    </div>
	</div>
	<div id="postbox-container-1">
	    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
	  
	    </div>
	</div>
	
</div>
</div>

sdfdslfhdgdfhgord;

	}


    function html_container($title,$bodytext)
    {
        return <<<sdfhdskfgdsafgfdsgafisdaigfdsufds
<div id="dashboard_site_health" class="postbox" style="">
<div class="postbox-header">
<h2 class="hndle ui-sortable-handle">{$title}</h2>
</div>
<div class="inside">
	<div class="health-check-widget">
	       {$bodytext}
	</div>

</div>
</div>
sdfhdskfgdsafgfdsgafisdaigfdsufds;

    }
}