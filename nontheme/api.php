<?php
/**
 * Created by PhpStorm.
 * User: CosMOs
 * Date: 12/13/2022
 * Time: 2:59 PM
 */
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
 $urls = $fnt->ajax_get_some_urls(7);

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

    $cache_data = $fnt->find_fastindex_data($slug);
    if(isset($cache_data['data']))
    {
        $dbdatas = json_decode($cache_data['data'],1);
        $response['data']  =$dbdatas;
        $response['response_type'] = 'cached';

    }else{
        $cw = new common_fapello_xworker();
        $cw->GetPageData($url);
        $dbdatas = $cw->get_json_data();
        $response['data']  =$dbdatas;
        $fnt->save_fastindex_data($slug,$dbdatas);
        $u_basedir = $_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/models/'.$slug .'/';
        $u_baseurl = 'https://ilovnudes.com/wp-content/uploads/models/'.$slug .'/';
        $data = $cw->build_wp_post_output($u_baseurl,$u_basedir);
        $response['response_type'] = 'live';
        }


   // print_r($dbdatas);



    $response['url'] = $url;
    $response['slug'] = $slug;
    $response['process'] = 'done';
}



$ytime = time();

$response['time_take'] =  $ytime - $xtime;
$response['time'] = $xtime;

header("Content-Type: application/json");
echo json_encode($response);


