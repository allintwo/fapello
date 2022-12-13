<?php


function Is_goodExt($filename,$need  = 'video')
{
    $ext = pathinfo(strtolower($filename), PATHINFO_EXTENSION);

    $vidoext = [
        "3g2","3gp","aaf","asf","avchd","avi","drc","flv","m2v","m4p","m4v","mkv","mng","mov","mp2","mp4","mpe","mpeg","mpg","mpv","mxf","nsv","ogg","ogv","ogm","qt","rm","rmvb","roq","srt","svi","vob","webm","wmv","yuv"
    ];
    $iamgeext = [
        "3dm","3ds","max","bmp","dds","gif","jpg","jpeg","png","psd","xcf","tga","thm","tif","tiff","yuv","ai","eps","ps","svg","dwg","dxf","gpx","kml","kmz","webp"
    ];

    if($need =='video')
    {
        foreach ($vidoext as $item)
        {
            if($ext == $item)
            {
                return true;
            }
        }
    }elseif ($need =='image')
    {
        foreach ($iamgeext as $item)
        {
            if($ext == $item)
            {
                return true;
            }
        }
    }
    return  false;
}

function print_json($data)
{
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

function downloadfile($url,$path)
{

    $host = parse_url(urldecode($url))['host'];
    //   /*

    $headers = [
        "Host: ".$host,
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:68.0) Gecko/20100101 Firefox/68.00".rand(0,999999),
        "Accept-Language: en-US,en;q=0.5",
        // "Accept-Encoding: gzip, deflate, br",
        "Referer: https://{$host}"
    ];


    set_time_limit(0);
//This is the file where we save the    information
    $fp = fopen ($path, 'w+');
//Here is the file we are downloading, replace spaces with %20
    $ch = curl_init(str_replace(" ","%20",$url));
    curl_setopt($ch, CURLOPT_TIMEOUT, 3000);
// write curl response to file
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// get curl response
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
}

function downloadVidfile($url,$path)
{

    $host = parse_url(urldecode($url))['host'];
    //   /*

    $headers = [
        "Host: {$host}",
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:88.0) Gecko/20100101 Firefox/88.0",
        "Accept: video/webm,video/ogg,video/*;q=0.9,application/ogg;q=0.7,audio/*;q=0.6,*/*;q=0.5",
        "Accept-Language: en-US,en;q=0.5",
        "Range: bytes=0-",
        "Connection: keep-alive",
        "Referer: https://{$host}/",
        "Pragma: no-cache",
        "Cache-Control: no-cache"
    ];


    set_time_limit(0);
//This is the file where we save the    information
    $fp = fopen ($path, 'w+');
//Here is the file we are downloading, replace spaces with %20
    $ch = curl_init(str_replace(" ","%20",$url));
    curl_setopt($ch, CURLOPT_TIMEOUT, 3000);
    // curl_setopt($ch, CURLOPT_HEADER, true); // header
    // curl_setopt($ch, CURLOPT_NOBODY, true); // header
// write curl response to file
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// get curl response
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
}


function downloadBigFile($file_source, $file_target) {
    $n = fopen($file_target,'w+');
    fclose($n);

    $rh = fopen($file_source, 'rb');
    $wh = fopen($file_target, 'w+b');
    if (!$rh || !$wh) {
        return false;
    }

    while (!feof($rh)) {
        if (fwrite($wh, fread($rh, 4096)) === FALSE) {
            return false;
        }
        echo ' ';
        flush();
    }

    fclose($rh);
    fclose($wh);

    return true;
}
