=== oik-block ===
Contributors: bobbingwide
Donate link: https://www.oik-plugins.com/oik/oik-donate/
Tags: shortcode, blocks
Requires at least: 4.9
Tested up to: 5.0-alpha
Gutenberg compatible: Yes
Stable tag: 0.0.0-alpha-20180409
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==
WordPress 5.0 blocks for oik shortcodes.

This is mostly prototype code delivering a number of the oik shortcodes 
as blocks for the new WordPress content editor.

oik delivers over 80 shortcodes. 
Some of these are crying out to be converted into advanced WordPress blocks.

This is just the start. 

This plugin is currently being used for education, demonstration, experimentation and estimation.

The code is being developed to work with latest version of the Gutenberg plugin,
upon which it is currently dependent, until the plugin is merged into core.

At some time in the future all references to Gutenberg may be eliminated.

We'll refer to Gutenberg as the new editor.
And each block will be called a block.

For more info on blocks see:

[block readme](https://github.com/bobbingwide/oik-block/tree/master/blocks)

This plugin also includes logic to evaluate Gutenberg's compatibility with site contents.
It forms opinions at multiple levels and applies these to decide which editor is the safest to use for the content and context.
At some point in the future this logic may be extracted into a separate plugin.

The batch routine, called oik-block-opinions.php, runs under the control of the oik-batch plugin. 
Its implementation is similar to WP-cli.



== Installation ==
1. Upload the contents of the oik-block plugin to the `/wp-content/plugins/oik-block' directory
1. Activate the oik-block plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

Where can I find out more?

[github bobbingwide oik-block ]


Yes, it's dependent upon a number of other plugins:

- oik 
- oik-bob-bing-wide
- gutenberg

and for the batch routine

- oik-batch

== Screenshots ==
1. oik-block's address block

== Upgrade Notice ==
= 0.0.0-alpha-20180409 =
Upgrade for two new blocks ( CSS and CSV ) and a fix to the Countdown block.

= 0.0.0-alpha-20180401 =
Provides 8 blocks and a batch routine to form opinions about content compatibility.

= 0.0.0 =
New plugin, available only from [github bobbingwide oik-block]

== Changelog == 
= 0.0.0-alpha-20180409 =
* Added: CSS block to replace bw_css shortcode [github bobbingwide oik-block issues 23]
* Added: CSV block to replace bw_csv shortcode [github bobbingwide oik-block issues 24]
* Changed: Update blocks/README.md - start fleshing out the build process [github bobbingwide oik-block issues 14]
* Changed: Update estimate spreadsheet and some .csv files [github bobbingwide oik-block issues 15]
* Changed: Update meta box to use logic in oik-block-opinions [github bobbingwide oik-block issues 18]
* Changed: move build files from assets to blocks/build	[github bobbingwide oik-block issue 12]
* Changed: opinion - Alter test for taxonomy show_in_rest to also check show_ui
* Fixed: Countdown block correct attribute name for expirytext - all lower case [github bobbingwide oik-block issues 11]
* Tested: With WordPress 4.9.5 and WordPress 5.0-alpha
* Tested: With PHP 7.1 and 7.2
* Built: With npm 5.6.0, node v8.9.4
* Built: With Gutenberg v2.6.0

= 0.0.0-alpha-20180401 =
* Added: First zipped version
* Changed: See GitHub commit history
* Tested: With Gutenberg 2.5.0
* Tested: With PHP 7.1 and 7.2
* Tested: With WordPress 4.9.4 and WordPress 5.0-

= 0.0.0 =
* Added: New plugin	2018/01/18

== Further reading ==
If you want to read more about the oik plugins then please visit the
[oik plugin](https://www.oik-plugins.com/oik) 
**"the oik plugin - for often included key-information"**

