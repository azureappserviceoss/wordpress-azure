<?php
/*
Plugin Name: Testplugin
Plugin URI: http://4itin.com/
Description: A Test Hello plugin used to demonstrate the process of creating plugins.
Version: 1.0
Author: yog
Author URI: http://4itin.com
License: GPL
*/

// Registor the page foe admin deskbord........
add_action('admin_menu', 'test_plugin_setup_menu'); 
add_action('admin_menu', 'test_plugin_setup_menu1'); 
function test_plugin_setup_menu(){
add_menu_page( 'Test Plugin Page', 
        'Add form', 'manage_options', 
        'test-plugin', 'test_form' );
}
function test_plugin_setup_menu1(){
add_menu_page( 'Test Plugin Page1', 
        'Add form1', 'manage_options', 
        'test-plugin', 'test_form1' );
}
add_shortcode( 'Test_SCode1','test_form1' );
add_shortcode( 'Test_SCode','test_form' );
function test_form1(){
echo "<h1>Hello World Test Plugin1!</h1>";
?>
<form method="post">
    Num1:<input type="text" name="n1"/><br>
    Num2:<input type="text" name="n2"/><br>
    <input type="submit" name="s1" value="Add"/><br>
    
</form>
<?php
if(isset($_POST['s1']))
{
    $a=$_POST['n1'];
    $b=$_POST['n2'];
    $c=$a+$b;
    echo "Add=".$c;
}
}
function test_form(){
echo "<h1>Hello World Test Plugin!</h1>";
?>
<form method="post">
    Num1:<input type="text" name="n1"/><br>
    Num2:<input type="text" name="n2"/><br>
    <input type="submit" name="s1" value="Add"/><br>
    
</form>
<?php
if(isset($_POST['s1']))
{
    $a=$_POST['n1'];
    $b=$_POST['n2'];
    $c=$a+$b;
    echo "Add=".$c;
}
 global $wpdb; 
   $res = $wpdb->get_results("select * from emp");
		   foreach ($res as $k) {
			echo "<br>".$k->id."  ".$k->name." ".$k->salary;
		}
}

class TestWidget extends WP_Widget
{
  function TestWidget()
  {
    $widget_ops = array('classname' => 'TestWidget',
        'description' => 'Displays a random post with thumbnail' );
    $this->WP_Widget('TestWidget', 'TestWidget', $widget_ops);
  }
 
  function form($instance)
  {
//admin form
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">
     Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
     name="<?php echo $this->get_field_name('title') ; ?>" type="text"
     value="<?php echo attribute_escape($title); ?>" />
      </label>
  </p>
<?php
  
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {

//User Output..........
   $title = apply_filters( 'widget_title', $instance['title'] );
 // before and after widget arguments are defined by themes
   echo $args['before_widget'];
   if ( ! empty( $title ) )
   echo $args['before_title'] . $title . $args['after_title'];
    // This is where you run the code and display the output
   echo '<h1>Hello Test <h1>';
   echo '<h1>This is Test Widget....</h1>';
  // global $wpdb; 
  // $res = $wpdb->get_results("select * from emp");
//foreach ($res as $k) {
 //   echo "<br>".$k->id."  ".$k->name." ".$k->salary;
//}
   echo $args['after_widget'];
  }
 
}

function wpb_load_widget() {
register_widget( 'TestWidget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );