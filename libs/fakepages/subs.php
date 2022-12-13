<?php

// modle list - as home

global $fapello_ajax;
global $is_user;


if($is_user)
{
    if(isset($_REQUEST['act']) && $_REQUEST['act'] =='unfollow')
    {
        $fapello_ajax->follow_model($_REQUEST['model_id'],0);
    }
}

$subs_html = '';
if(1)
{
    $subs_html = $fapello_ajax->a_get_subs_by_userid(0,200);
}



echo <<<wseljkfhdsdkjfgdskfgdsiufdsg
<script>
    ajax_data.pagename = 'subs';
</script>

				<div class="max-w-2xl mx-auto">
					<h1 class="lg:text-2xl text-lg font-extrabold leading-none text-gray-900 tracking-tight mb-5"> My Subscriptions </h1>
					<div class="justify-center lg:space-x-10 lg:space-y-0 space-y-5">
							<div class="bg-white dark:bg-gray-900 shadow-md rounded-md overflow-hidden">

								<div class="bg-gray-50 dark:bg-gray-800 border-b border-gray-100 flex items-baseline justify-between py-4 px-6 dark:border-gray-800">
									<h2 class="font-semibold text-lg">My Girls</h2>
								</div>	
								<div class="divide-gray-300 divide-gray-50 divide-opacity-50 divide-y px-4 dark:divide-gray-800 dark:text-gray-100">
								{$subs_html}
								</div>
							</div>
					</div>			   
				</div>

wseljkfhdsdkjfgdskfgdsiufdsg;
