<?php



add_filter( 'wpseo_sitemap_index', 'add_sitemap_custom_items' );

function add_sitemap_custom_items( $sitemap_custom_items ) {
    $site_url = site_url();
    $index = 1;
    $timestamp = time() - 86000;
   // return $sitemap_custom_items;

    $lastmod = date('c', $timestamp); ;

    $sitemap_indexs = generate_xhfsdioufg_sitemap_file();
    foreach ($sitemap_indexs as $sitemap_index)
    {
        $sitemap_custom_items .= "
<sitemap>
<loc>{$sitemap_index}</loc>
<lastmod>{$lastmod}</lastmod>
</sitemap>";
    }


    /* Add Additional Sitemap
     * This section can be removed or reused as needed
     */
   // $sitemap_custom_items .= '';

    /* DO NOT REMOVE ANYTHING BELOW THIS LINE
     * Send the information to Yoast SEO
     */
    return $sitemap_custom_items;
}


function generate_xhfsdioufg_sitemap_file()
{
    global $wpdb;
    $limit = 40000;
    $keeploop =1;

    $sitemap_indexs = [];


    $home_path = $_SERVER['DOCUMENT_ROOT'].'/';
    $home_url = 'https://ilovnudes.com/';
    $i = 0;

    while ($keeploop)
    {
        $page = $i;
        $pgx = $page * $limit;


        $save_loc = $home_path ."Models-images-{$i}.xml";
        $sitemap_indexs[] = $home_url."Models-images-{$i}.xml";
        $sitemap_config = array(
            "SITE_URL" => "https://mcpedl.gg/",
            "ALLOW_EXTERNAL_LINKS" => false,
            "ALLOW_ELEMENT_LINKS" => false,
            "CRAWL_ANCHORS_WITH_ID" => "",
            "KEYWORDS_TO_SKIP" => array(),
            "SAVE_LOC" => $save_loc,
            "PRIORITY" => 0.5,
           // "CHANGE_FREQUENCY" => "daily",
            "CHANGE_FREQUENCY" => "weekly",
            "LAST_UPDATED" => date('Y-m-d'),
        );


       // $query = "SELECT * FROM `fape_media` order by id asc limit $pgx,$limit";
        $query = "SELECT mo.name,me.id FROM fape_media me,fape_model mo WHERE mo.id = me.model_id ORDER BY me.id ASC limit $pgx,$limit";
        $results = $wpdb->get_results($query);
        $ttl = $results->num_rows;
       // echo $ttl;
        if($ttl != $limit)
        {
            $keeploop = 0;
        }
        $scaned_url = [];
        foreach ($results as $result)
        {
//            $id = $result->id;
//            $model_id = $result->model_id;
//            $time = $result->time;
//            $type = $result->type;
           // $link = $result->link;
            $mname = sanitize_title($result->name);

            $nlink = "https://ilovnudes.com/Model/{$mname}/{$result->id}/";

            $scaned_url[] = $nlink;
        }
        $smg = new SitemapGeneratorXXY($sitemap_config);
        $smg->generateFile($scaned_url);
        $i++;
    }
    return $sitemap_indexs;

}



//// class --------
class SitemapGeneratorXXY
{
    // Config file with crawler/sitemap options
    private $config;

    // Array containing all scanned pages
    private $scanned;

    // The base of the given site url
    // EXAMPLE: https://student-laptop.nl
    private $site_url_base;

    // File where sitemap is written to.
    private $sitemap_file;

    // Constructor sets the given file for internal use
    public function __construct($conf)
    {
        // Setup class variables using the config
        $this->config = $conf;
        $this->scanned = [];
        $this->site_url_base = parse_url($this->config['SITE_URL'])['scheme'] . "://" . parse_url($this->config['SITE_URL'])['host'];
        $this->sitemap_file = fopen($this->config['SAVE_LOC'], "w");
    }

    public function GenerateSitemap()
    {
        // Call the recursive crawl function with the start url.
        $this->crawlPage($this->config['SITE_URL']);

        // Generate a sitemap with the scanned pages.
        $this->generateFile($this->scanned);
    }

    // Get the html content of a page and return it as a dom object
    private function getHtml($url)
    {
        // Get html from the given page
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $html = curl_exec($curl);
        curl_close($curl);

        //Load the html and store it into a DOM object
        $dom = new DOMDocument();
        @$dom->loadHTML($html);

        return $dom;
    }

    // Recursive function that crawls a page's anchor tags and store them in the scanned array.
    private function crawlPage($page_url)
    {
        $url = filter_var($page_url, FILTER_SANITIZE_URL);

        // Check if the url is invalid or if the page is already scanned;
        if (in_array($url, $this->scanned) || !filter_var($page_url, FILTER_VALIDATE_URL)) {
            return;
        }

        // Add the page url to the scanned array
        array_push($this->scanned, $page_url);

        // Get the html content from the
        $html = $this->getHtml($url);
        $anchors = $html->getElementsByTagName('a');

        // Loop through all anchor tags on the page
        foreach ($anchors as $a) {
            $next_url = $a->getAttribute('href');

            // Check if there is a anchor ID set in the config.
            if ($this->config['CRAWL_ANCHORS_WITH_ID'] != "") {
                // Check if the id is set and matches the config setting, else it will move on to the next anchor
                if ($a->getAttribute('id') != "" || $a->getAttribute('id') == $this->config['CRAWL_ANCHORS_WITH_ID']) {
                    continue;
                }
            }

            // Split page url into base and extra parameters
            $base_page_url = explode("?", $page_url)[0];

            if (!$this->config['ALLOW_ELEMENT_LINKS']) {
                // Skip the url if it starts with a # or is equal to root.
                if (substr($next_url, 0, 1) == "#" || $next_url == "/") {
                    continue;
                }
            }

            // Check if the given url is external, if yes it will skip the iteration
            // This code will only run if you set ALLOW_EXTERNAL_LINKS to false in the config.
            if (!$this->config['ALLOW_EXTERNAL_LINKS']) {
                $parsed_url = parse_url($next_url);
                if (isset($parsed_url['host'])) {
                    if ($parsed_url['host'] != parse_url($this->config['SITE_URL'])['host']) {
                        continue;
                    }
                }
            }

            // Check if the link is absolute or relative.
            if (substr($next_url, 0, 7) != "http://" && substr($next_url, 0, 8) != "https://") {
                $next_url = $this->convertRelativeToAbsolute($base_page_url, $next_url);
            }

            // Check if the next link contains any of the pages to skip. If true, the loop will move on to the next iteration.
            $found = false;
            foreach ($this->config['KEYWORDS_TO_SKIP'] as $skip) {
                if (strpos($next_url, $skip) || $next_url === $skip) {
                    $found = true;
                }
            }

            // Call the function again with the new URL
            if (!$found) {
                $this->crawlPage($next_url);
            }
        }
    }

    // Convert a relative link to a absolute link
    // Example: Relative /articles
    //			Absolute https://student-laptop.nl/articles
    private function convertRelativeToAbsolute($page_base_url, $link)
    {
        $first_character = substr($link, 0, 1);
        if ($first_character == "?" || $first_character == "#") {
            return $page_base_url . $link;
        } else if ($first_character != "/") {
            return $this->site_url_base . "/" . $link;
        } else {
            return $this->site_url_base . $link;
        }
    }

    // Function to generate a Sitemap with the given pages array where the script has run through
    public function generateFile($pages)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
		<!-- ' . count($pages) . ' total pages-->
		<!-- PHP-sitemap-generator by https://github.com/tristangoossens -->';


        // Print the amount of pages
       // echo count($pages);

        foreach ($pages as $page) {
            $xml .= "<url><loc>" . $page . "</loc>
            <lastmod>" . $this->config['LAST_UPDATED'] . "</lastmod>
            <changefreq>" . $this->config['CHANGE_FREQUENCY'] . "</changefreq>
            <priority>" . $this->config['PRIORITY'] . "</priority></url>";
        }

        $xml .= "</urlset>";
        $xml = str_replace('&', '&amp;', $xml);

        // Format string to XML
        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = FALSE;
        $dom->loadXML($xml);
        $dom->formatOutput = TRUE;

        // Write XML to file and close it
        fwrite($this->sitemap_file, $dom->saveXML());
        fclose($this->sitemap_file);
    }
}