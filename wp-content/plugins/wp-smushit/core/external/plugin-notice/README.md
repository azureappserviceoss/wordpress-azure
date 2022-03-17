# Usage Examples
## Minimum requirement
```
<?php
require_once 'recommended-plugins-notice/notice.php';

do_action(
    'wpmudev-recommended-plugins-register-notice',
    plugin_basename(__FILE__), // Plugin basename
    'My Plugin Name', // Plugin Name
    array(
        'top_level_page_screen_id' // Screen IDs
    ),
);
```

# Development Mode
## Always ON
This code below will always show the notice on every page.
```
<?php
require_once 'recommended-plugins-notice/notice.php';

add_filter( 'wpmudev-recommended-plugins-is-displayable', '__return_true' );
add_filter(
	'wpmudev-recommended-plugin-active-registered',
	function () {
		$active           = new WPMUDEV_Recommended_Plugins_Notice_Registered_Plugin( 'basename' );
		$active->selector = array( 'after', '.sui-wrap .sui-header' );
		$active->name     = 'Sample';

		return $active;
	}
);
```
## Custom time trigger
Default of notice to be displayed in plugin page(s) is **14** days after its registered.
You can decrease or even increase this because why not.
```
<?php
add_filter(
	'wpmudev-recommended-plugins-notice-display-seconds-after-registered',
	function ( $time_trigger ) {
		// 1 minute trigger
		$time_trigger = 1 * MINUTE_IN_SECONDS;

		return $time_trigger;
	}
);
```
## Un-dismiss
Accidentally or purposed-ly dismiss the notice for whatever reason ? this below code can undo that.
```
<?php
add_action(
 	'wpmudev-recommended-plugins-before-display',
 	function () {
 		WPMUDEV_Recommended_Plugins_Notice::get_instance()->un_dismiss();
 	}
);
```
