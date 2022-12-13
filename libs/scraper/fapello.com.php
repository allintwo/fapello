<?php

class common_fapello_xworker{

    public $Weburl = '';
    public $WebData = '';
    public $Title = '';
    public $basedir = '';
    public $baseurl = '';


    public $fimage = '';
    public $name = '';
    public $slug = '';
    public $twitter = '';
    public $instagram = '';
    public $onlyfans = '';
    public $patreon = '';
    public $images = [];
    public $videos = [];


    function build_wp_post_output($baseurl,$basedir)
    {
        $this->basedir = $basedir;
        $this->baseurl = $baseurl;




        if(is_dir($basedir)) // create folder if needed
        {

        }else{
            mkdir($basedir,0777,1);
        }
        $permit = 0777;
        chmod($basedir, $permit);

        $contents = '';


        $ximg_url = $this->fimage;
        $this->images[] = $this->fimage;
        // download images ?
        $i=0;
        foreach ($this->images as $imgx)
        {
            // $fname = basename($imgx[1]); // little fix
            $fname= basename(parse_url($imgx)['path']);
            if(Is_goodExt($fname,'image'))
            {
                if(strlen($this->fimage)<10)
                {
                    $this->fimage = $imgx;
                }

            }else{
                $fname = basename($imgx) .'n.jpg';
              //  $app->Content = str_replace($imgx[0],'',$app->Content);
                continue;
            }
            $fname = urldecode($fname);
            $savepath = $basedir .$fname;
            $imgxurl = $baseurl . $fname;
            //echo $mask.$imgx[1];

            if(is_file($savepath))
            {

            }else{
                downloadfile($imgx,$savepath);
                $ximg_url = $imgxurl;
            }
            $contents .= "<div class='fap-xph' style='width: 100%'><img loading='lazy' class='fap-xphto' src='{$imgxurl}'/> </div>";
        }


        $tag_desc = [
            // 'image' => $this->fimage,
            'image' => $ximg_url,
            'twitter'=> $this->twitter,
            'onlyfans' => $this->onlyfans,
            'instagram' => $this->instagram,
            'patreon' => $this->patreon,
            'about' => $this->name
        ];


        $output = [
            'title'=>$this->name,
            'slug' => $this->slug,
            'content' => $contents,
            'image' => $this->fimage,
            'tag_desc'=>  json_encode($tag_desc)
        ];

        return $output;
    }

    private function post_content_build()
    {



        $output = <<<dlkvfdlkghfdghfdkghsdfdsh
<div class="fapello-content-area">
<h2>{$this->name}</h2>

</div>
dlkvfdlkghfdghfdkghsdfdsh;


    }



    function get_json($json =1)
    {

        $jsval = [
            'cover_image' => $this->fimage,
            'name' => $this->name,
            'slug' => $this->slug,
            'twitter' => $this->twitter,
            'instagram' => $this->instagram,
            'onlyfans' => $this->onlyfans,
            'patreon' => $this->patreon,
            'images' => $this->images,
            'videos' => $this->videos
        ];

        if($json)
        {
            return  json_encode($jsval);
        }
        return $jsval;
    }

    public function GetPageList($limit = 30)
    {
        $urls = [];
        for($i=0;$i<10;$i++)
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
            if(count($urls)> $limit)
            {
                break;
            }
        }
        $urls = array_slice($urls,0,$limit);
        $this->Url_list = $urls;
        return $urls;
    }


    public function GetPageData($url)
    {
        $this->Weburl = $url;
        if($this->WebData == '')
        {
            $this->WebData = $this->func_get_content($url);
        }

        /// title
        $dom = str_get_html($this->WebData);

        if(1)
        {
            $this->name =  $dom->find('h2.font-semibold',0)->innertext;
            $this->slug = basename($url);

        }
        // get thumb
        if(1) // no easy image
        {
            $fimg = $dom->find('div.w-56 a img');
            foreach ($fimg as $img)
            {
                if(isset($img->attr['src']))
                {
                    $this->fimage = $img->attr['src'];
                }
            }

        }
        if(1)
        {
           // $mname = $dom->find('h2.font-semibold',0)->innertext;
            $social_links = $dom->find('p.text-center a');
            foreach ($social_links as $social_link)
            {
                $link = $social_link->href;
                if(strpos($link,'twitter'))
                {
                    $this->twitter = $link;
                }
                if(strpos($link,'instagram'))
                {
                    $this->instagram = $link;
                }
                if(strpos($link,'onlyfans'))
                {
                    $this->onlyfans = $link;
                }
                if(strpos($link,'patreon'))
                {
                    $this->patreon = $link;
                }
            }

            // get contents
            // https://fapello.com/ajax/model/helly-von-valentine/page-2/
            $img_urls = [];

            $images = $this->get_all_image_url($this->slug);
            foreach ($images as $key => $image_url)
            {
                $img_urls[] = $image_url;
            }
            $this->images = $img_urls;
        }

    }


    function get_all_image_url($model_slug)
    {
        $image_urls = [];

        $keeploop = 1;
        $i = 0;

        while ($keeploop)
        {
            $url = "https://fapello.com/ajax/model/{$model_slug}/page-{$i}/";
            $i++;
            $page_data = $this->func_get_content($url);
            $dom = str_get_html($page_data);
            $imgs = $dom->find('img');
            $x = 0;
            foreach ($imgs as $img)
            {
                $iurl = $img->src;
                $iurl = str_replace('_300px.','.',$iurl);

                if(strpos($iurl,'.svg'))
                {

                }else{
                    $ikey = md5($iurl);
                    $image_urls[$ikey] = $iurl;
                    $x++;
                }
            }
            if($x <31)
            {
                $keeploop = 0;
            }

          //  $keeploop =0;
            if($i > 25)
            {
                $keeploop =0;
            }

        }

        return $image_urls;

    }

    function func_get_content($myurl, $method = 'get', $posts = [], $headers = [],$encoding=0)
    {

      //  sleep(rand(0,3));
        $host = parse_url(urldecode($myurl))['host'];
        //   /*
        if($headers == [])
        {
            $headers = [
                "Host: ".$host,
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:68.0) Gecko/20100101 Firefox/68.00".rand(0,999999),
                "Accept-Language: en-US,en;q=0.5",
                // "Accept-Encoding: gzip, deflate, br",
                "Connection: keep-alive",
                "Upgrade-Insecure-Requests: 1",
                "TE: Trailers",];
        }
        // */

        $myurl = str_replace(" ","%20",$myurl);
        // global $range;
        $ch = curl_init();

        //  $agent = 'tab mobile';
        // curl_setopt($ch, CURLOPT_PROXY, '85.26.146.169:80');
        curl_setopt($ch, CURLOPT_URL, $myurl);
        // curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);

        //  curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt');
        //  curl_setopt( $ch, CURLOPT_COOKIEFILE,dirname(__FILE__) . '/cookie.txt');
        //  curl_setopt($ch, CURLOPT_HEADER, true); // header
        // curl_setopt($ch, CURLOPT_NOBODY, true); // header
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        //  curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_RANGE, $range);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch,CURLOPT_TIMEOUT , 60);
        # sending manually set cookie
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if($method != 'get')
        {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($posts));
        }

        //  curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"serialno\":\"$code\"}");


        //   $error = curl_error($ch);
        $result = curl_exec($ch);
        curl_close($ch);
        if($encoding)
        {
            return mb_convert_encoding($result, 'utf-8','auto');
        }

        // debug
        //  file_put_contents($this->ROOT.'/webpage.txt',$result);

        return $result;
        //  return mb_convert_encoding($result, 'UTF-8','auto');
    }

}