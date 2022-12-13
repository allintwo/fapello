<?php


class fapello_admin_subpage{

    public $name = 'fapello';
    public $PagesPath = '';
    public $call = 'display';
    public $menupage = 'fapello_main.php';


    function __construct($PagesPath = '') {
        if($PagesPath == '') {
            $this->PagesPath = get_template_directory() . '/libs/menus/';
        }

        add_action('admin_menu', [$this,'my_menu_pages']);
    }

    function my_menu_pages(){
        $menupage = $this->menupage;
        $menu_pagetitle = $this->name.'-TiTile';
        $menu_menutitle = $this->name.'-TITLE';
        $menu_menuslug = $this->menupage;
        $menu_callfunc = $this->call;

        $menu_class = $this;

        if(is_file($this->PagesPath .'/'.$menupage))
        {
            include_once $this->PagesPath .'/' .$menupage;
            $clsxnm= substr($menupage,0,-4);
            $menu_class = new $clsxnm();
            $menu_pagetitle = $menu_class->pagetitle;
            $menu_menutitle = $menu_class->menutitle;
            $menu_callfunc = $menu_class->call;

        }

        add_menu_page($menu_pagetitle, $menu_menutitle, 'manage_options', $menu_menuslug, [$menu_class,$menu_callfunc],'dashicons-images-alt2' );

        $pagesx = scandir($this->PagesPath);


        foreach ($pagesx as $pages)
        {
            $xfiile = $this->PagesPath . $pages;
            if(is_file($xfiile))
            {
                if($menupage == $pages)
                {
                    continue;
                }
                // echo 'yah baby';
                $pagetitle = 'PageTitle';
                $menutitle = 'MenuTitle';
                $pagefunction = 'mypage';
                include_once $xfiile;
                $pagefunction = substr($pages,0,-4);
                $pageclass = new $pagefunction();
                $pagetitle = $pageclass->pagetitle;
                $menutitle = $pageclass->menutitle;


                $pagefunction = substr($pages,0,-4);
                add_submenu_page($menu_menuslug, $pagetitle, $menutitle, 'manage_options', $pages,[$pageclass,$pageclass->call]);
                //	add_submenu_page('myrpf-main', 'Options', 'Plugin Options', 'manage_options', 'myrpf-options',[$this,'my_menu_output2'] );

            }
        }



        //add_submenu_page('myrpf-status', 'Options', 'Plugin Options', 'manage_options', 'myrpf-options',[$this,'my_menu_output2'] );
    }

    // remove main.php page and edit here or edit pages/main.php
    function display()
    {
        echo '<h1> please add main.php page </h1>';
    }

}

new fapello_admin_subpage();