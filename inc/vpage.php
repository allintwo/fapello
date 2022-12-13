<?php


class VirtualPage
{

    private $query;
    private $title;
    private $content;
    private $template;
    private $wp_post;

    function __construct($query = '/index2', $template = 'page', $title = 'Untitled')
    {
        $this->query = filter_var($query, FILTER_SANITIZE_URL);
        $this->setTemplate($template);
        $this->setTitle($title);
    }

    function getQuery()
    {
        return $this->query;
    }

    function getTemplate()
    {
        return $this->template;
    }

    function getTitle()
    {
        return $this->title;
    }

    function setTitle($title)
    {
        $this->title = filter_var($title, FILTER_SANITIZE_STRING);

        return $this;
    }

    function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    public function updateWpQuery()
    {

        global $wp, $wp_query;

        // Update the main query
        $wp_query->current_post = $this->wp_post->ID;
        $wp_query->found_posts = 1;
        $wp_query->is_page = true;//important part
        $wp_query->is_singular = true;//important part
        $wp_query->is_single = false;
        $wp_query->is_attachment = false;
        $wp_query->is_archive = false;
        $wp_query->is_category = false;
        $wp_query->is_tag = false;
        $wp_query->is_tax = false;
        $wp_query->is_author = false;
        $wp_query->is_date = false;
        $wp_query->is_year = false;
        $wp_query->is_month = false;
        $wp_query->is_day = false;
        $wp_query->is_time = false;
        $wp_query->is_search = false;
        $wp_query->is_feed = false;
        $wp_query->is_comment_feed = false;
        $wp_query->is_trackback = false;
        $wp_query->is_home = false;
        $wp_query->is_embed = false;
        $wp_query->is_404 = false;
        $wp_query->is_paged = false;
        $wp_query->is_admin = false;
        $wp_query->is_preview = false;
        $wp_query->is_robots = false;
        $wp_query->is_posts_page = false;
        $wp_query->is_post_type_archive = false;
        $wp_query->max_num_pages = 1;
        $wp_query->post = $this->wp_post;
        $wp_query->posts = array($this->wp_post);
        $wp_query->post_count = 1;
        $wp_query->queried_object = $this->wp_post;
        $wp_query->queried_object_id = $this->wp_post->ID;
        $wp_query->query_vars['error'] = '';
        unset($wp_query->query['error']);

        $GLOBALS['wp_query'] = $wp_query;

        $wp->query = array();
        $wp->register_globals();

    }

    public function createPage()
    {
        if (is_null($this->wp_post)) {
            $post = new stdClass();
            $post->ID = -99;
            $post->ancestors = array(); // 3.6
            $post->comment_status = 'closed';
            $post->comment_count = 0;
            $post->filter = 'raw';
            $post->guid = home_url($this->query);
            $post->is_virtual = true;
            $post->menu_order = 0;
            $post->pinged = '';
            $post->ping_status = 'closed';
            $post->post_title = $this->title;
            $post->post_name = sanitize_title($this->template); // append random number to avoid clash
            $post->post_content = $this->content ?: 'no content';
            $post->post_excerpt = '';
            $post->post_parent = 0;
            $post->post_type = $this->template;
            $post->post_status = 'publish';
            $post->post_date = current_time('mysql');
            $post->post_date_gmt = current_time('mysql', 1);
            $post->modified = $post->post_date;
            $post->modified_gmt = $post->post_date_gmt;
            $post->post_password = '';
            $post->post_content_filtered = '';
            $post->post_author = is_user_logged_in() ? get_current_user_id() : 0;
            // $post->post_content = $this->content;
            $post->post_mime_type = '';
            $post->to_ping = '';

            $this->wp_post = new WP_Post($post);
            $this->updateWpQuery();

            @status_header(200);
            wp_cache_add(-99, $this->wp_post, 'posts');

        }


        return $this->wp_post;
    }
}

/**
 * @param $pattarn 'Must have (group)'
 * @param $title
 * @param $body_html
 */

function custom_fake_page_handle($pattarn,$title,$body_html)
{
    global $wp;
    global $media_id;
    global $model_name;
    global $vpage;

    //$url_path = parse_url($wp->request)["path"];
    $url_path = strtok($wp->request, '?');

    $current_url = '/'. add_query_arg(array(), $url_path);
    if(preg_match($pattarn,$current_url,$mtc))
    {
        $model_name = $mtc[1];
        $vpage = 'media-single';
        $media_id = $mtc[2];
        $page = new VirtualPage( $pattarn, 'page',__($model_name) );
        $page->setContent("oh my dear");
        $page->createPage();
        return;
    }

}


add_action( 'template_redirect', function () {

   // print_r(get_query_var( 'name' ,''));
    global $wp;
    global $media_id;
    global $model_name;
    global $vpage;
    $current_url = '/'. add_query_arg(array(), $wp->request);




    $pattarn = "#/Model/(.*)/(\d+)#";
    if(preg_match($pattarn,$current_url,$mtc))
    {
        $vpage = 'media-single';
        $model_name = $mtc[1];
        $media_id = $mtc[2];
        $page = new VirtualPage( $pattarn, 'page',__($model_name) );
        $page->setContent("oh my dear");
        $page->createPage();
        return;
    }

    $pattarn = "#^/trending$#";
    if(preg_match($pattarn,$current_url,$mtc))
    {
        $vpage = 'trending';
        $model_name = $mtc[1];
        $media_id = $mtc[2];
        $page = new VirtualPage( $pattarn, 'page',__('Some trending Models') );
        $page->setContent("oh my dear");
        $page->createPage();
        return;
    }

    $pattarn = "#^/videos#";
    if(preg_match($pattarn,$current_url,$mtc))
    {
        $vpage = 'videos';
        $model_name = $mtc[1];
        $media_id = $mtc[2];
        $page = new VirtualPage( $pattarn, 'search',__('Some trending Models') );
        $page->setContent("oh my dear");
        $page->createPage();
        return;
    }
    $pattarn = "#^/random#";
    if(preg_match($pattarn,$current_url,$mtc))
    {
        $vpage = 'random';
        $model_name = $mtc[1];
        $media_id = $mtc[2];
        $page = new VirtualPage( $pattarn, 'search',__('Some Random Models') );
        $page->setContent("oh my dear");
        $page->createPage();
        return;
    }
    $pattarn = "#^/top-likes#";
    if(preg_match($pattarn,$current_url,$mtc))
    {
        $vpage = 'top-likes';
        $model_name = $mtc[1];
        $media_id = $mtc[2];
        $page = new VirtualPage( $pattarn, 'search',__('Some trending Models') );
        $page->setContent("oh my dear");
        $page->createPage();
        return;
    }
    $pattarn = "#^/top-followers#";
    if(preg_match($pattarn,$current_url,$mtc))
    {
        $vpage = 'top-followers';
        $model_name = $mtc[1];
        $media_id = $mtc[2];
        $page = new VirtualPage( $pattarn, 'search',__('Some trending Models') );
        $page->setContent("oh my dear");
        $page->createPage();
        return;
    }
    $fakepage_url_pattarns = [
        '#^/(my)$#',
        '#^/(subs)$#',
        '#^/(discussion)$#',
        '#^/(insert-by-url)$#',
        '#^/(my-likes)$#',
        '#^/(search)$#',
        '#^/(cronjobs)$#',
        '#^/(comments)$#',
        '#^/(contacts)$#',
    ];
    foreach ($fakepage_url_pattarns as  $fakepage_url)
    {
        $pattarn = $fakepage_url;
        if(preg_match($pattarn,$current_url,$mtc))
        {
            $vpage = $mtc[1];
            $model_name = $mtc[1];
           // $media_id = $mtc[2];
            $page = new VirtualPage( $pattarn, 'search',__('My feed') );
            $page->setContent("oh my dear");
            $page->createPage();
            return;
        }
    }

    /*
        switch ( get_query_var( 'name' ,'') ) {

            case 'NediaX':
                // http://yoursite/contact  ==> loads page-contact.php
                $page = new VirtualPage( "/media/\d+/", 'contact',__('Contact Me h') );
                $page->setContent("oh my dear");
                $page->createPage();
                break;

            case 'archiveX':
                // http://yoursite/archive  ==> loads page-archive.php
                $page = new VirtualPage( "/archive2", 'archive' ,__('Archives'));
                $page->createPage();
                break;

            case 'blogX':
                // http://yoursite/blog  ==> loads page-blog.php
                $page = new VirtualPage( "/blog2", 'blog' ,__('Blog'));
                $page->createPage();
                break;
        }
    */

},1 );