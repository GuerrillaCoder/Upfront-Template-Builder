**UPDATE: I've tested this and it looks like it is working correclty.  The only issue would be if WPMUDev change the Upfront_virtual_region class and it breaks the override.  I started a thread here: https://premium.wpmudev.org/forums/topic/upfront_virtual_region-class-not-adding-modules-and-wrappers#post-962941 hopefully a developer will give an opinion on the approach

Video Showing Usage: https://dl.dropboxusercontent.com/u/19226543/usingplugin.mp4**

# Upfront-Template-Builder
Helps generate the PHP layout files for Upfront child themes

*Please note: This is a quick hack to make building child theme templates easier for me.  Any helper code I will update here and welcome any contributions but seeing as WPMU are releasing a proper template builder soon I won't waste my time trying to make it pretty or validating it.  You use this at your own risk and it is assumed you know what you are doing.*

## Why did I make this?

Upfront is a very promising templating system but building the layouts are a pain.  The templating system is basically a Json wrapper but you can't just use your saved layouts to make page templates, you need to build a PHP array which the template wrapper then converts into Json.

I got fed up typing arrays out so I made this so I can just build the layout in the GUI Upfront designer, save to database then use this to generate the PHP for the layout file.

I haven't really tested this much but it seems to work.  Please let me know if you have any issues as I am using this on my own site.

**You will have to manage updating the child theme class and global settings yourself if you want to make a completely new child theme.  I will get around to building a property merger later on if I get time**

## Instructions

1. Create your custom layout that you would like your user to be able to select as a page template and save it
2. Enter the pages name as it appears in the wp_options table minus the storage key.  e.g. single-page-123
3. Select the sections you want to export to the layout and hit export
4. Copy the PHP to your layout file

Let me know if you find it useful and would like something added.

# Setting up layout files:

## Update functions.php to allow us to add an existing region

in your child themes functions.php file add this class.  This will allow us to add in regions that already have data for wrappers and modules:

```php
class Upfront_Virtual_Region_From_Existing extends Upfront_Virtual_Region
{
    public function __construct ($args, $properties = array()) {

        $this->modules = $args["modules"];
        $this->wrappers = $args["wrappers"];

        parent::__construct($args, $properties);
    }
}
```

## Creating template file
Add template file to the root of the theme named: page_tpl-{name}.php (replace "{name}" with the name for your template).

Add this code to the page:

```php
<?php
/**
 * Template Name: {name} Page template
 *
 * @package WordPress
 * @subpackage {name}
 */

the_post();
$layout = Upfront_Output::get_layout(array('specificity' => 'single-page-{name}'));

get_header();
echo $layout->apply_layout();
get_footer();
```

## Update your required pages in settings.php

Settings.php has an associative array.  You need to find:
```
required_pages' => '{...}',
```
You need to append this entry for your template:

```
required_pages' => '{... existing entries ..},{\\"yourTemplateName\\":{\\"name\\":\\"yourTemplateName\\",\\"slug\\":\\"yourTemplateName\\",\\"layout\\":\\"single-page-yourTemplateName\\"}',
```

## Add Your layout file

In your themes layouts folder create a php file called single-page-{name}.php.

You can now paste the output from the plugin into this file and you should have a working template.
