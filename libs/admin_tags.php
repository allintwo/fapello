<?php
function load_media_files() {
    wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'load_media_files' );


// jQuery
wp_enqueue_script('jquery');
// This will enqueue the Media Uploader script
wp_enqueue_media();

add_action( 'admin_footer', 'my_footer_scriptsxx' );
function my_footer_scriptsxx(){
?>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('#upload-btn').click(function(e) {
                e.preventDefault();
                var image = wp.media({
                    title: 'Upload Image',
                    // mutiple: true if you want to upload multiple files at once
                    multiple: false
                }).open()
                    .on('select', function(e){
                        // This will return the selected image from the Media Uploader, the result is an object
                        var uploaded_image = image.state().get('selection').first();
                        // We convert uploaded_image to a JSON object to make accessing it easier
                        // Output to the console uploaded_image
                        console.log(uploaded_image);
                        var image_url = uploaded_image.toJSON().url;
                        // Let's assign the url value to the input field
                        $('#model_image').val(image_url);
                    });
            });
        });
        function isJsonString(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        }
        function disenable_elem(id)
        {
            var eleman = document.getElementById(id);
            eleman.style.display = 'none';

            // eleman.setAttribute("disabled", false);
            //  eleman.setAttribute("editable", false);
        }

        // create - just create
        var etmodel_image = document.getElementById('model_image');
        var etmodel_description = document.getElementById('model_description');
        var etmodel_twitter = document.getElementById('twitter');
        var etmodel_instagram = document.getElementById('instagram');
        var etmodel_onlyfans = document.getElementById('onlyfans');


        if(1)
        {
          var  jsdata = {
                image: '',
                twitter: '',
                onlyfans:'',
                instagram:'',
                about:''
            };


            var editfrmmyEle = document.getElementById("edittag");
            if(editfrmmyEle){
                disenable_elem('description');
                var descrdata = document.getElementById('description').innerText;

                // load
                if(isJsonString(descrdata))
                {
                    jsdata = JSON.parse(descrdata);
                    etmodel_image.value = jsdata.image
                    etmodel_description.value = jsdata.about
                    etmodel_twitter.value = jsdata.twitter
                    etmodel_instagram.value = jsdata.instagram
                    etmodel_onlyfans.value = jsdata.onlyfans
                }
                // build - create
                var tm_raw_contentx = document.getElementById('description');
                function creatbtnclickmyFunction2() {
                    jsdata.image = etmodel_image.value;
                    jsdata.about = etmodel_description.value;
                    jsdata.twitter = etmodel_twitter.value;
                    jsdata.instagram = etmodel_instagram.value;
                    jsdata.onlyfans = etmodel_onlyfans.value;
                    tm_raw_contentx.innerHTML =  JSON.stringify(jsdata);
                }
                var crtbtnx = document.getElementsByClassName('button-primary')[0];
                crtbtnx.addEventListener("click", creatbtnclickmyFunction2);
               // crtbtnx.addEventListener('mouseover', creatbtnclickmyFunction2);

            }else{
                // add new
                disenable_elem('tag-description');
                var tm_raw_content = document.getElementById('tag-description');
                function creatbtnclickmyFunction() {
                    jsdata.image = etmodel_image.value;
                    jsdata.about = etmodel_description.value;
                    jsdata.twitter = etmodel_twitter.value;
                    jsdata.instagram = etmodel_instagram.value;
                    jsdata.onlyfans = etmodel_onlyfans.value;

                    tm_raw_content.innerHTML =  JSON.stringify(jsdata);
                }
                var crtbtn = document.getElementById('submit');
                crtbtn.addEventListener("click", creatbtnclickmyFunction);
            }




        }
    </script>

<?php
}


add_action('admin_footer', 'fapelloadd_this_script_footer');

function fapelloadd_this_script_footer(){
 return <<<lfhduikgdoauyfgdsouygsisd

lfhduikgdoauyfgdsouygsisd;
}


$tags_items_html = <<<sdkjsdjkfhdskjfdsgfkjdsgfkdsf

<tr class="form-group row form-field">
            <th>Image</th>
            <td>
                <input id="model_image" name="model_image" placeholder="Upload/select Image" type="text" class="form-control">
            <button id="upload-btn">Select file</button>
            </td>
</tr>
<tr>
            <th>Description</th>
            <td>
                <textarea id="model_description" name="model_description" cols="40" rows="5" class="form-control"></textarea>
            </td>
</tr>
<tr class="form-group row form-field">
            <th>Twitter url</th>
            <td>
                <input id="twitter" name="twitter" placeholder="https://" type="text" class="form-control">
            </td>
</tr>
<tr class="form-group row form-field">
            <th>Instagram</th>
            <td>
                <input id="instagram" name="instagram" placeholder="https://" type="text" class="form-control">
            </td>
</tr>
<tr class="form-group row form-field">
            <th>Onlyfans</th>
            <td>
                <input id="onlyfans" name="onlyfans" placeholder="https://" type="text" class="form-control">
            </td>
</tr>
sdkjsdjkfhdskjfdsgfkjdsgfkdsf;






$x_js_script = '';

function misha_add_term_fields( $taxonomy ) {
    global $tags_items_html;
    global $x_js_script;

    echo"<table class='form-table' role='presentation'><tbody> $tags_items_html</tbody></table> $x_js_script";;

}
add_action( 'Model_add_form_fields', 'misha_add_term_fields' );
add_action( 'Model_edit_form_fields', 'misha_edit_term_fields', 10, 2 );

function misha_edit_term_fields( $term, $taxonomy ) {
global $tags_items_html;
global $x_js_script;
  //  $value = get_term_meta( $term->term_id, 'misha-text', true );
    echo  $tags_items_html.$x_js_script ;
}


/*

add_action( 'Model_term_new_form_tag', function()
{
    // Model_term_new_form_tag
    // {$taxonomy}_add_form_fields
    printf( '><div class="form-field">%s</div', esc_html__( 'Some content', 'mydomain' ) );
}, PHP_INT_MAX );


add_action( 'Model_add_form_fields', 'misha_add_term_fields' );

function misha_add_term_fields( $taxonomy ) {

    echo '<div class="form-field">
	<label for="misha-text">Text Field</label>
	<input type="text" name="misha-text" id="misha-text" />
	<p>Field description may go here.</p>
	</div>';

}

add_action( 'Model_edit_form_fields', 'misha_edit_term_fields', 10, 2 );

function misha_edit_term_fields( $term, $taxonomy ) {

    $value = get_term_meta( $term->term_id, 'misha-text', true );

    echo '<tr class="form-field">
	<th>
		<label for="misha-text">Text Field</label>
	</th>
	<td>
		<input name="misha-text" id="misha-text" type="text" value="' . esc_attr( $value ) .'" />
		<p class="description">Field description may go here.</p>
	</td>
	</tr>';

}
add_action( 'created_Model', 'misha_save_term_fields' );
add_action( 'edited_Model', 'misha_save_term_fields' );

function misha_save_term_fields( $term_id ) {

    update_term_meta(
        $term_id,
        'misha-text',
        sanitize_text_field( $_POST[ 'misha-text' ] )
    );

}


add_filter( 'simple_register_taxonomy_settings', 'misha_fields' );

function misha_fields( $fields ) {

    $fields[] = array(
        'id'	=> 'mishatest',
        'taxonomy' => array( 'Model' ),
        'fields' => array(
            array(
                'id' => 'misha-text',
                'label' => 'Text Field',
                'type' => 'text',
            ),
        )
    );

    return $fields;

}

*/