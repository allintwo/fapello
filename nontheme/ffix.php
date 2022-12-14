<?php
/**
 * Created by PhpStorm.
 * User: CosMOs
 * Date: 12/13/2022
 * Time: 11:00 PM
 */
ini_set('max_execution_time',0);
ini_set('memory_limit',0);

function get_index_letter($slug)
{
        $firstlatter = $slug[0];
        return $firstlatter;
}

function make_beutyfuldir($mod_dir)
{
    if(is_dir($mod_dir))
    {

    }else{
        mkdir($mod_dir,0777,1);
    }
}


function update_content_upload_folder_indexing()
{
    $target_folder = $_SERVER['DOCUMENT_ROOT'].'//wp-content/uploads/models';
    $directories = glob($target_folder . '/*' , GLOB_ONLYDIR);
    foreach ($directories as $directory)
    {
        $fnme = basename( $directory);
        if(strlen($fnme)>1)
        {

            $fbsdr = dirname($directory);

                $newdir = $fbsdr .'/'.$fnme[0] .'/'.$fnme;
                make_beutyfuldir($newdir);
                rename($directory,$newdir);
          //  exit();
        }

    }
    $indexstr = '';

}

function update_database()
{
    $db_name = 'ilovnudes_nudes';
    $db_user = 'ilovnudes_nudes';
    $db_password = 'g9Oi;12GL%0i';
    $db_host = 'localhost';

    $mysqli = new mysqli($db_host,$db_user,$db_password,$db_name);
    $query = $mysqli->query("SELECT * FROM `fape_media` order by time");
    while ($row = mysqli_fetch_assoc($query))
    {
        $link = $row['link'];
        $slash_count = substr_count($link,'/');
        $time = time();
        if($slash_count == 7)
        {
            $flnme = basename($link);
            $dirnm1 = dirname($link);
            $slug = basename($dirnm1);
            $dirnm2 = dirname($dirnm1);
            $newindexlink = $dirnm2 .'/'.$slug[0].'/'.$slug.'/'.$flnme;
            $mysqli->query("UPDATE `fape_media` SET `time`='{$time}',`link`='{$newindexlink}' WHERE id = '{$row['id']}'");
            // update
          $index_folder =   get_index_letter($slug);
        }elseif($slash_count =='8')
        {

        }else{
            //something wrong
        }

    }
}
echo 'do not run';
// update_database();

// update_content_upload_folder_indexing();