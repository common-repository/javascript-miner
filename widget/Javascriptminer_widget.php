<?php
/*
 * @since             1.2
 * @package           Javascriptminer
 *
 * @wordpress-plugin
 * Plugin Name:       Javascript Miner
 * Description:       Enable CoinHive integration on your site. Coinhive offers a JavaScript miner for the Monero Blockchain that you can embed in your website.
 * Version:           1.1.4
 * Author:            marcobb81
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       javascriptminer
 * Domain Path:       /languages

*/ 
class JavascriptminerSimpleMiner_Widget extends WP_Widget
{
  function JavascriptminerSimpleMiner_Widget()
  {
    $widget_ops = array('classname' => 'JavascriptminerSimpleMiner_Widget', 'description' => 'JSMiner Simple Miner UI');
    $this->WP_Widget('JavascriptminerSimpleMiner_Widget', 'JSMiner Simple Miner UI', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args((array) $instance, array( 'title' => '',  'autostart' => 0, 'thread' => 1, 'start_text' => 'Support me using your CPU power. You can stop it anytime.', 'text-color' => '#000000'));
    $title = $instance['title'];
    $autostart = $instance['autostart'];
    $thread = $instance['thread'];
    $start_text = $instance['start_text'];
    $text_color = $instance['text-color'];
?>
    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>">Title: 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
        </label>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('autostart'); ?>">
            <input type="checkbox" id="<?php echo $this->get_field_id('autostart'); ?>" name="<?php echo $this->get_field_name('autostart'); ?>" value="1" <?php echo checked( 1, attribute_escape($autostart), false ) ?> /> Autostart
        </label>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('thread'); ?>">Performance for Medium use ( 1 is Low, 10 is High): 
            <input class="widefat" id="<?php echo $this->get_field_id('thread'); ?>" name="<?php echo $this->get_field_name('thread'); ?>" type="number" value="<?php echo attribute_escape($thread); ?>" step="1" min="2" max="9"/>
        </label>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('start-text'); ?>">Text to display before Start/Stop button: 
            <input class="widefat" id="<?php echo $this->get_field_id('start_text'); ?>" name="<?php echo $this->get_field_name('start_text'); ?>" type="text" value="<?php echo attribute_escape($start_text); ?>" />
        </label>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('text-color'); ?>">Text Color: 
            <input class="widefat" id="<?php echo $this->get_field_id('text-color'); ?>" name="<?php echo $this->get_field_name('text-color'); ?>" type="text" value="<?php echo attribute_escape($text_color); ?>" />
        </label>
    </p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    $instance['autostart'] = $new_instance['autostart'];
    $instance['text-color'] = $new_instance['text-color'];
    $instance['thread'] = $new_instance['thread'];
    $instance['start_text'] = $new_instance['start_text'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    
    $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
    $key = get_option('javascriptminer_site_key') ;
    $autostart = ($instance['autostart']==1) ? 'true' : 'false';
    $text_color = empty($instance['text-color']) ? '#000' : $instance['text-color'];
    $thread = empty($instance['thread']) ? '1' : $instance['thread'];
    $start_text = empty($instance['start_text']) ? 'Support me using your CPU power. You can stop it anytime.' : $instance['start_text'];
 
    if (!empty($title))
      echo $before_title . $title . $after_title;
 
    // Do Your Widgety Stuff Here...
    if ($style == "coinhive") {
    ?>
    <script src="https://coinhive.com/lib/miner.min.js" async></script>
    <div class="coinhive-miner" 
        style="width: 256px; height: 310px"
        data-key="<?php echo $key ?>"
        data-autostart="<?php echo $autostart ?>"
        data-text="<?php echo $text_color ?>"
        data-action="<?php echo $text_color ?>"
        data-graph="<?php echo $text_color ?>"
        data-threads="<?php echo $thread ?>"
        data-throttle="<?php echo $throttle ?>"
        data-start="<?php echo $start_text ?>"
        <em>Please disable Adblock!</em>
    </div>    
    <?php
    }
    else {
    ?>
        <style>.mining-button { color: <?php echo $text_color; ?>!important; }</style>
        <div id="jsminer-widget-controls" data-autostart="<?php echo $autostart ?>">
            <p><?php echo $start_text; ?></p>
            <div class="mining-button" id="jsminer-widget-start">
                <svg class="mining-icon play-button" viewBox="0 0 200 200" alt="Start Mining">
                    <circle cx="100" cy="100" r="90" fill="none" stroke-width="15" class="mining-stroke"></circle>
                    <polygon points="70, 55 70, 145 145, 100" class="mining-fill"></polygon>
                </svg>
                Start Mining
            </div>
            <div class="mining-button" id="jsminer-widget-stop">
                <svg class="mining-icon pause-button" viewBox="0 0 200 200" alt="Pause">
                    <circle cx="100" cy="100" r="90" fill="none" stroke-width="15" class="mining-stroke"></circle>
                    <rect x="70" y="50" width="20" height="100" class="mining-fill"></rect>
                    <rect x="110" y="50" width="20" height="100" class="mining-fill"></rect>
                </svg>
                Stop Mining
            </div>
            <div id="jsminer-widget-performance">
                <div class="mining-button" id="jsminer-widget-low" data-performance="1">Low</div>
                <div class="mining-button" id="jsminer-widget-medium" data-performance="<?php echo $thread ?>">Medium</div>
                <div class="mining-button" id="jsminer-widget-high" data-performance="10">High</div>
            </div>
            <div id="jsminer-widget-error">
                Error. Please disable Adblock.
            </div>
        </div>   
    <?php
    } 
    echo $after_widget;
  }
}
// widget
add_action( 'widgets_init',  create_function('', 'register_widget("JavascriptminerSimpleMiner_Widget");') );

?>