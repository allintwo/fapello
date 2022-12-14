<?php
/**
 * Created by PhpStorm.
 * User: CosMOs
 * Date: 12/13/2022
 * Time: 3:52 PM
 */

class fapello_nontheme{
    public  $mysqli = null;

    function __construct(mysqli  $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    function get_option($key,$by = 'option_name')
    {
        $query = $this->mysqli->query("SELECT * FROM `options` WHERE {$by} = '{$key}'");
        while ($row = mysqli_fetch_assoc($query))
        {
            return $row;
        }
        return [];
    }
    function add_option($name,$value)
    {
        $time = time();
        $name = $this->mysqli->real_escape_string($name);
        $value = $this->mysqli->real_escape_string($value);
        $this->mysqli->query("INSERT INTO `options`(`option_name`, `option_value`, `time`) 
VALUES ( '$name','{$value}','{$time}')");
    }
    function update_option_by_id($value,$id = 'id')
    {
        $time = time();
        $value = $this->mysqli->real_escape_string($value);
        $this->mysqli->query("UPDATE `options` SET option_value='{$value}',`time`='{$time}' WHERE id = '{$id}'");
    }
    function update_option_by_option_name($value,$key_name)
    {
        $time = time();
        $value = $this->mysqli->real_escape_string($value);
        $this->mysqli->query("UPDATE `options` SET option_value='{$value}',`time`='{$time}' WHERE option_name = '$key_name'");
    }

    function t_all_urls($url)
    {
        $slug = basename($url);
        $time = time();
        $url = $this->mysqli->real_escape_string($url);
        $slug = $this->mysqli->real_escape_string($slug);
        $this->mysqli->query("INSERT INTO `all_urls`(`url`, `slug`, `status`, `time`) VALUES ('{$url}','{$slug}','0','{$time}')");
        //echo $this->mysqli->error;
       // echo 'adding.';
    }


    function cron_scrape_models()
    {

        global $response;
        $current_page = (int)$this->get_option('current_page')['option_value'];
        $list_per_run_page = (int)$this->get_option('list_per_run')['option_value'];
        $max_last_page = (int)$this->get_option('last_page')['option_value'];
        $urls = [];
//echo $this->mysqli->error;

        $fp = $current_page;
        $lp = $current_page + $list_per_run_page;
        if($lp > $max_last_page)
        {
            $lp = $max_last_page;
        }
        if($fp == $max_last_page)
        {
            $response['logs'][] = "cu{$current_page} lper{$list_per_run_page} mx{$max_last_page} lp{$lp} fp{$fp}".'All done';
        }
$i = 0;

        for($i=$fp;$i<$lp;$i++)
        {
            $url = "https://fapello.com/ajax/index/page-{$i}/";
            $result = $this->func_get_content($url);
            $dom = str_get_html($result);
            $links = $dom->find('div.items-center a');
            foreach ($links as  $link) {
                $mylink = $link->href;
                if(strpos($mylink,'/feed/')>0)
                {

                }else{
                    $urls[md5($mylink)] = $mylink;
                }
            }

        }

        foreach ($urls as $key => $url)
        {
           // echo $url .PHP_EOL;
            $this->t_all_urls($url);
        }
        $this->update_option_by_option_name($i,'current_page');
     $response['logs'][]  = 'all model done';
    }

    function update_urls_time($id)
    {
        $time = time();
      //  $this->mysqli->query("UPDATE `all_urls` SET `time`='{$time}' WHERE id = '{$id}'");
        $this->mysqli->query("UPDATE `all_urls` SET `time`='{$time}',`status`='1' WHERE id = '{$id}'");
    }
    function ajax_get_some_urls($limit =50)
    {

        $urls = [];
        $query_text = '';
        if(rand(0,2))
        {
            $query = $this->mysqli->query("select * from all_urls where status = '0' order by time asc limit $limit");
        }else{
            $query = $this->mysqli->query("select * from all_urls where status = '0' ORDER BY rand() LIMIT $limit");
        }
       // $query = $this->mysqli->query("SELECT * FROM `all_urls` order by status asc limit $limit");

        while ($row = mysqli_fetch_assoc($query))
        {
            $urls[] = $row['url'];
            $this->update_urls_time($row['id']);

        }
        return $urls;
    }

    function update_fast_index_time($id)
    {
        $time = time();
        $this->mysqli->query("UPDATE `fastindex` SET `utime`='{$time}' WHERE id = '{$id}'");
    }
    function ajax_get_indexed_urls($limit =50)
    {

        $urls = [];
        $query = $this->mysqli->query("select * from fastindex order by utime asc limit $limit");
        while ($row = mysqli_fetch_assoc($query))
        {
            $urls[] = "https://fapello.com/". $row['slug'] .'/';

            $this->update_fast_index_time($row['id']);
        }
        return $urls;
    }

    function export_all_urls()
    {
        $urls = [];
        $url_text = '';
        $query = $this->mysqli->query("select * from all_urls order by time asc");
        while ($row = mysqli_fetch_assoc($query))
        {
            $urls[] = $row['url'];
        }

        $url_text = implode(PHP_EOL,$urls);
        file_put_contents('fapello_urls.txt',$url_text);

    }

    function find_fastindex_data($slug)
    {
        $slug = $this->mysqli->real_escape_string($slug);
        $query = $this->mysqli->query("SELECT * FROM `fastindex`  where slug = '{$slug}'");
        while ($row = mysqli_fetch_assoc($query))
        {
            return $row;
        }
        return  [];
    }


    function get_index_data_by_url_slug($slug)
    {
        global $response;
        $url = "https://fapello.com/{$slug}/";
       // $slug = basename($url);
        $cache_data = $this->find_fastindex_data($slug);
        if(isset($cache_data['data']))
        {
            $dbdatas = json_decode($cache_data['data'],1);
            if(isset($dbdatas['images'][0]))
            {
                $response['data']  =$dbdatas;
            }

            $response['response_type'] = 'cached';

        }else{
            $cw = new common_fapello_xworker();
            $cw->GetPageData($url);
            $dbdatas = $cw->get_json_data();
            $this->save_fastindex_data($slug,$dbdatas);
            if(isset($dbdatas['images']))
            {
                $response['data']  =$dbdatas;
                $u_basedir = $_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/models/'.$slug[0].'/'.$slug .'/';
                $u_baseurl = 'https://ilovnudes.com/wp-content/uploads/models/'.$slug[0].'/'.$slug .'/';
                $cw->build_wp_post_output($u_baseurl,$u_basedir);
            }


            $response['response_type'] = 'live';
        }
    }


    function save_fastindex_data($slug,$data)
    {
        if($this->find_fastindex_data($slug))
        {
            return;
        }
        $data = json_encode($data);
        $slug = $this->mysqli->real_escape_string($slug);
        $data = $this->mysqli->real_escape_string($data);
        $time = time();
        $this->mysqli->query("INSERT INTO `fastindex`(`slug`, `data`, `ctime`, `utime`, `status`) 
VALUES ('{$slug}','{$data}','{$time}','{$time}','1')");

    }

    function func_get_content($myurl, $method = 'get', $posts = [], $headers = [],$encoding=0)
    {
        //  sleep(rand(0,3));
        $host = parse_url(urldecode($myurl))['host'];
        if($headers == [])
        {
            $headers = [
                "Host: ".$host,
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:68.0) Gecko/20100101 Firefox/68.00".rand(0,999999),
                "Accept-Language: en-US,en;q=0.5",
                "Connection: keep-alive",
                "Upgrade-Insecure-Requests: 1",
                "TE: Trailers",];
        }
        $myurl = str_replace(" ","%20",$myurl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $myurl);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch,CURLOPT_TIMEOUT , 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if($method != 'get')
        {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($posts));
        }
        $result = curl_exec($ch);
        curl_close($ch);
        if($encoding)
        {
            return mb_convert_encoding($result, 'utf-8','auto');
        }
        return $result;
    }
}