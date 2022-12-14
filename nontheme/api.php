<?php
/**
 * Created by PhpStorm.
 * User: CosMOs
 * Date: 12/13/2022
 * Time: 2:59 PM
 */
$response_type = 'json';
$response_text = '';
if(isset($_REQUEST['response_type']))
{
    $response_type = $_REQUEST['response_type'];
}
$start_time = time();
$response = [];
$response['status'] = 400;
$response['data'] = '';
$response['errors'] = [];
$response['logs'] = [];
$response['actions'] = [
    'allmodels' => ['about' => 'internal scrape model data from fapello'],
    'get_some_urls' => ['about' => 'using ajax you can get some old urls, can set limit 50','limit'=>50],
    'exportallurls' => ['about' => 'you can download all exported links as text file.']
];

ini_set('error_reporting',1);
error_reporting(1);
ini_set('display_errors',1);

//
//    $db_name = 'faylab_fapello_tools';
//    $db_user = 'faylab_fapello_tools_user';
//    $db_password = '9AGAaVf88grWL6Y';
//    $db_host = 'localhost';

    $db_name = 'ilovnudes_fapello_tools';
    $db_user = 'ilovnudes_fapello_tools_user';
    $db_password = '9AGAaVf88grWL6Y';
    $db_host = 'localhost';




// last page https://fapello.com/page-19200/


$mysqli = new mysqli($db_host,$db_user,$db_password,$db_name);

ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
include_once 'common_func.php';
include_once 'simple_html_dom.php';
include_once 'fapello.com.php';
include_once 'fapello_nontheme.php';
$fnt = new fapello_nontheme($mysqli);

$xtime = time();

$action = '';
if(isset($_REQUEST['action'])){
    $action = $_REQUEST['action'];
}
//

if($action =='allmodels')
{
 //   print_r($urls);
   $response['logs'][] = 'aciton all models';
    $fnt->cron_scrape_models();
}
if($action =='get_some_urls')
{
    $limit = 50;
    if(isset($_REQUEST['limit']))
    {
        $limit = $_REQUEST['limit'];
    }
    $urls =  $fnt->ajax_get_some_urls($limit);
    $response['urls'] = $urls;
}
if($action =='exportallurls')
{
    $fnt->export_all_urls();
    header('location: fapello_urls.txt');
    exit();
}





if($action =='bgindexing')
{


    // work by time system
    $t_ttm = time();
    $t_mx_tm = 62;
    ini_set('max_execution_time',$t_mx_tm + 10);// second
    $t_mx_tm = $t_mx_tm - 5;

    $keeploop = 1;
    while ($keeploop)
    {
        $t_enttm = time();
        $t_difftm = time() - $t_ttm;
        if($t_mx_tm < $t_difftm)
        {
            $keeploop = 0;
        }

        $urls = $fnt->ajax_get_some_urls(3); // 7
        $response['urls'] = $urls;
        foreach ($urls as $url)
        {
            $response['logs'][] = $url."--Indexed";
            $newfnt = new fapello_nontheme($mysqli);
            $slug = basename($url);
            $newfnt->get_index_data_by_url_slug($slug);

            $t_enttm = time();
            $t_difftm = time() - $t_ttm;
            if($t_mx_tm < $t_difftm)
            {

                $keeploop = 0;
                break;
            }
        }
        $t_enttm = time();
        $t_difftm = time() - $t_ttm;
        if($t_mx_tm < $t_difftm)
        {
            $keeploop = 0;
        }
    }

/*
    $max_file_index = 8;
    if(isset($_REQUEST['limit']))
    {
        $max_file_index = $_REQUEST['limit'];
    }
    for($i = 0;$i<$max_file_index;$i++)
    {
        $urls = $fnt->ajax_get_some_urls(1); // 7
        $response['urls'] = $urls;
        foreach ($urls as $url)
        {
            $response['logs'][] = $url."--Indexed";
            $newfnt = new fapello_nontheme($mysqli);
            $slug = basename($url);
            $newfnt->get_index_data_by_url_slug($slug);
        }
    }
    */
}

if($action =='fastindex')
{
    $url = 'https://fapello.com/frasy-strase/';

    if(isset($_REQUEST['url']))
    {
        $url = $_REQUEST['url'];
    }else{
        $response['data'] = [];
        $response['errors'][] = 'url not set';
        return;
    }

    $slug = basename($url);
    $fnt->get_index_data_by_url_slug($slug);
   // print_r($dbdatas);
    $response['url'] = $url;
    $response['slug'] = $slug;
    $response['process'] = 'done';
}
if($action =='get_indexed_urls')
{
    $limit = 50;
    if(isset($_REQUEST['limit']))
    {
        $limit = $_REQUEST['limit'];
    }
    $urls =  $fnt->ajax_get_indexed_urls($limit);
    if($response_type =='text')
    {
        echo implode(PHP_EOL,$urls);exit();
    }
    $response['urls'] = $urls;
}

$ytime = time();

$response['time_take'] =  $ytime - $xtime;
$response['time'] = $xtime;

$end_time = time();
$total_time = $end_time - $start_time;
header("Content-Type: application/json");

$response['start_time'] = $start_time;
$response['end_time'] = $end_time;
$response['total_sec'] = $total_time;
echo json_encode($response);


