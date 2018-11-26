# oik-block 
![banner](https://raw.githubusercontent.com/bobbingwide/oik-block/master/assets/oik-block-banner-772x250.jpg)
* Contributors: bobbingwide
* Donate link: https://www.oik-plugins.com/oik/oik-donate/
* Tags: gutenberg, compatibility, oik
* Requires at least: 4.9
* Tested up to: 5.0-RC1
* Gutenberg compatible: Yes
* Stable tag: 0.1.0-alpha-20181126
* License: GPLv3 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html

## Description 
Gut feel - helps you form Gutenberg compatibility opinions.

oik-block may help you evaluate the compatibility of Gutenberg with your site's configuration and content.

- It contains logic to evaluate Gutenberg's compatibility with site contents.
- It forms opinions at multiple levels and applies these to help you decide which editor is the safest to use for the content and context.
- These opinions are displayed in the Preferred Editor meta box.

Shortcodes provided
- [blocks] - displays the raw post content - may help you debug your blocks
- [content] - displays the website content summary
- [guts] - displays some information about Gutenberg



The oik-block-opinions.php batch routine helps you to estimate the compatibility of your site's content with Gutenberg.

- The routine runs under the control of the oik-batch plugin; its implementation is similar to WP-cli.
* - Syntax: oikwp oik-block-opinions.php subcommand [post_type |  post_id ]  url=domain path=path
* - where subcommand may be: status, reset, list, decide


The oik-block plugin was developed for education, demonstration, experimentation and estimation.



## Installation 
1. Upload the contents of the oik-block plugin to the `/wp-content/plugins/oik-block' directory
1. Activate the oik-block plugin through the 'Plugins' menu in WordPress


## Frequently Asked Questions 

Where can I find out more?

https://github.com/bobbingwide/oik-block/


* Note: For Gutenberg blocks for oik-shortcodes see https://github.com/bobbingwide/oik-blocks.


Yes, it's dependent upon a number of other plugins:

- oik - for shortcodes
- oik-bwtrace - for logic associated with hooked functions
- Gutenberg

and for the batch routine ( oik-block-opinions.php )

- oik-batch

## Screenshots 
1.

## Upgrade Notice 
# 0.1.0-alpha#20181126 
No longer contains any Gutenberg blocks, which were moved to oik-blocks.

# 0.0.0-alpha-20181118 
Version for cwiccer.com testing compatibility with oik plugins.

# 0.0.0-alpha-20181022 
Upgrade for Gutenberg v4.0.0 support.

# 0.0.0-alpha-20180409 
Upgrade for two new blocks ( CSS and CSV ) and a fix to the Countdown block.

# 0.0.0-alpha-20180401 
Provides 8 blocks and a batch routine to form opinions about content compatibility.

# 0.0.0 
New plugin, available only from https://github.com/bobbingwide/oik-block

## Changelog 
# 0.1.0-alpha-20181126 
* Changed: Only do meta box processing if 'oik_admin_menu' has been run https://github.com/bobbingwide/oik-block/issues/27
* Changed: check for file existence before attempting to load it in oik_block_hook_checker::check_prerequisites https://github.com/bobbingwide/oik-block/issues/25
*
* a4300e3 Issue #28 - add dependencies on wp-components and wp-editor

# 0.0.0-alpha-20181118 
* Added: Prototype generic dynamic shortcode block https://github.com/bobbingwide/oik-block/issues/16
* Changed: Update GitHub Issue block for Gutenberg v4.0.0 https://github.com/bobbingwide/oik-block/issues/1
* Changed: Update for Gutenberg 4.x and WordPress 5.0-betax https://github.com/bobbingwide/oik-block/issues/28
* Changed: Update oik-nivo for Gutenberg 4.0.0 and higher https://github.com/bobbingwide/oik-block/issues/13
* Changed: Update oik-person for Gutenberg v4.0.0 https://github.com/bobbingwide/oik-block/issues/7
* Deleted: Delete Zac Gordon's examples https://github.com/bobbingwide/oik-block/issues/12

# 0.0.0-alpha-20181022 
* Changed: Update Countdown block for Gutenberg 4.0.0 https://github.com/bobbingwide/oik-block/issues/11
* Changed: Update oik-csv for Gutenberg 4.0.0 https://github.com/bobbingwide/oik-block/issues/24
* Changed: Update oik-css for Gutenberg 4.0.0 https://github.com/bobbingwide/oik-block/issues/23
* Changed: Update blocks README for Gutenberg 4.0.0 https://github.com/bobbingwide/oik-block/issues/28
* Changed: Improve hook checking logic where the hook is not invoked in Gutenberg https://github.com/bobbingwide/oik-block/issues/25
* Changed: Adjust attached hook counts for replace_editor - removed trace hook functions
* Changed: Update compare-hooks README. Add using results, move other stuff to sub-directories

# 0.0.0-alpha-20180409 
* Added: CSS block to replace bw_css shortcode https://github.com/bobbingwide/oik-block/issues/23
* Added: CSV block to replace bw_csv shortcode https://github.com/bobbingwide/oik-block/issues/24
* Changed: Update blocks/README.md - start fleshing out the build process https://github.com/bobbingwide/oik-block/issues/14
* Changed: Update estimate spreadsheet and some .csv files https://github.com/bobbingwide/oik-block/issues/15
* Changed: Update meta box to use logic in oik-block-opinions https://github.com/bobbingwide/oik-block/issues/18
* Changed: move build files from assets to blocks/build	https://github.com/bobbingwide/oik-block/issues/12
* Changed: opinion - Alter test for taxonomy show_in_rest to also check show_ui
* Fixed: Countdown block correct attribute name for expirytext - all lower case https://github.com/bobbingwide/oik-block/issues/11
* Tested: With WordPress 4.9.5 and WordPress 5.0-alpha
* Tested: With PHP 7.1 and 7.2
* Built: With npm 5.6.0, node v8.9.4
* Built: With Gutenberg v2.6.0

# 0.0.0-alpha-20180401 
* Added: First zipped version
* Changed: See GitHub commit history
* Tested: With Gutenberg 2.5.0
* Tested: With PHP 7.1 and 7.2
* Tested: With WordPress 4.9.4 and WordPress 5.0-

# 0.0.0 
* Added: New plugin	2018/01/18

## Further reading 
If you want to read more about the oik plugins then please visit the
[oik plugin](https://www.oik-plugins.com/oik)
**"the oik plugin - for often included key-information"**

