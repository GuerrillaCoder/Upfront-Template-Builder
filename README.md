**UPDATE: Success!  Got it working smoothly.  I will update the code and readme in a few mins**

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
