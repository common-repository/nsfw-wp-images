<?php
/*
Plugin Name: NSFW WP Images
Plugin URI: http://example.com/wordpress-plugins/my-plugin
Description: Описание плагина
Version: 0.1
Author: Evgeniy Burmakin
Author URI: http://freika.ru/
License: GPLv2
*/


$nsfw_wpi_name = "NSFW WP Images";
     
function nsfw_wpi_add_admin() {
  global $nsfw_wpi_name;
  add_options_page(__('Settings').': '.$nsfw_wpi_name, $nsfw_wpi_name, 'edit_themes', basename(__FILE__), 'nsfw_wpi_to_admin');
}
 
// Вид административной страницы и обработка-запоминание получаемых опций
 
function nsfw_wpi_to_admin() {
  global $nsfw_wpi_name;
?>
 
<div class="wrap">
<?php 
screen_icon(); // Значок сгенерируется автоматически
echo '<h2>'.__('Settings').': '.$nsfw_wpi_name.'</h2>'; // Заголовок
// Пошла обработка запроса
if (isset($_POST['save'])) {
    if (($_POST['jquery'] == 1))
        update_option('nsfw_wpi_radio', 1);
    else
        update_option('nsfw_wpi_radio', 0);

    if (($_POST['button'] == 1))
        update_option('nsfw_wpi_button', 1);
    else
        update_option('nsfw_wpi_button', 0);
    echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><b>'.__('Settings saved.').'</b></p></div>';
}
// Внешний вид формы
?>
  <form method="post">
    <table class="form-table">
      <tr valign="top">
        <th scope="row">jQuery:</th>
        <td>
            <fieldset>
            <legend class="jquery-or-not">
            <span>jQuery</span>
            </legend>
            <label for="use_jquery">
            <input name="jquery" id="use_jquery" type="radio" value="1" <?php if(get_option('nsfw_wpi_radio')==1) { echo 'checked'; } ?>>
            Use internal jQuery(Google CDN)
            </label>
            <br>
            <label for="dont_use_jquery">
            <input name="jquery" id="dont_use_jquery" type="radio" value="0" <?php if(get_option('nsfw_wpi_radio')==0) { echo 'checked'; } ?>>
            Don't use internal jQuery(use own/theme)
            </label>
            </fieldset>
        </td>
      </tr>
     <tr>
         <th scope="row">NSFW Button</th>
         <td>
             <fieldset>
                 <legend class="button-or-not">
                 <span>Show NSFW button?(this option toggles displaying pre-installed NSFW button)</span>
                 </legend>
                 <label for="show_button">
                 <input name="button" id="show_button" type="radio" value="1" <?php if(get_option('nsfw_wpi_button')==1) { echo 'checked'; } ?>>
                 Yes
                 </label>
                 <br>
                 <label for="dont_show_button">
                 <input name="button" id="dont_show_button"type="radio" value="0" <?php if(get_option('nsfw_wpi_button')==0) { echo 'checked'; } ?>>
                 No
                </label>
             </fieldset>
         </td>
     </tr>
         <?php /*
        if(get_option('nsfw_wpi_button') == 1) {
            ?>
    <tr>
        <th scope="row">NSFW Button Options</th>
         <td>
             <fieldset>
                 <legend class="nsfw_button_css">
                 <span>Enter your own css for NSFW button:</span>
                 </legend>
                 <label for="nsfw_button_css">
<textarea name="nsfw_button_css" id="nsfw_button_css" cols="60" rows="10">
background-color: red;
padding: 5px;
</textarea>
                 </label>
            


    </tr>
            <?php */
        }



    ?>
    </table>


You can insert NSFW button where you want, using <i>[nsfw_wpi_button]</i> shortcode.

  <div class="submit">
      <input name="save" type="submit" class="button-primary" value="<?php echo __('Save Draft'); ?>" />
  </div>
</form>
 
</div>
<?php
}
 
// Итоговые действия
 
add_action('admin_menu', 'nsfw_wpi_add_admin'); 
 
// Никаких следов после деинсталляции
// О работе хука я уже писал в одной из своих предыдущих записей
 
if (function_exists('register_uninstall_hook'))
  register_uninstall_hook(__FILE__, 'nsfw_wpi_deinstall');
   
function nsfw_wpi_deinstall() {
  delete_option('nsfw_wpi_radio');
}
 






if (get_option('nsfw_wpi_radio') == 1) {
    function nsfw_jquery() {
    if (!is_admin()) {
        wp_deregister_script('jquery'); 
        wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js', false, '1.8.2'); 
        wp_enqueue_script('jquery');

        
    }
}
add_action('init', 'nsfw_jquery');
}

if (get_option('nsfw_wpi_button') == 1) {
    function nsfw_button() {
    echo "<div id='nsfw-wrapper'><span class='nsfw-button' id='nsfw-toggle'>NSFW</span></div>";
}
add_action('wp_head', 'nsfw_button');
}



function nsfw_script()  
{  
    // Register the script like this for a plugin:  
    wp_register_script( 'nsfw', plugins_url( 'nsfw.js', __FILE__ ) );  
    // or  
    // Register the script like this for a theme:  
    wp_register_script( 'nsfw', get_template_directory_uri() . 'nsfw.js' );  
  
    // For either a plugin or a theme, you can then enqueue the script:  
    wp_enqueue_script( 'nsfw' );  
}  
add_action( 'wp_enqueue_scripts', 'nsfw_script', 11 );  


function nsfw_style()  
{  
    // Register the style like this for a plugin:  
    wp_register_style( 'style', plugins_url( 'style.css', __FILE__ ), array(), '20120208', 'all' );  
    // or  
    // Register the style like this for a theme:  
    wp_register_style( 'style', get_template_directory_uri() . '/style.css', array(), '20120208', 'all' );  
  
    // For either a plugin or a theme, you can then enqueue the style:  
    wp_enqueue_style( 'style' );  
}  
add_action( 'wp_enqueue_scripts', 'nsfw_style' );  

function nsfw_wpi_button( $atts ) {
     echo "<div id='nsfw-wrapper'><span class='nsfw-button' id='nsfw-toggle'>NSFW</span></div>";
}
add_shortcode('nsfw_wpi_button', 'nsfw_wpi_button');


?>