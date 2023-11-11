=== oik-block ===
Contributors: bobbingwide
Donate link: https://www.oik-plugins.com/oik/oik-donate/
Tags: gutenberg, compatibility, oik, shortcodes
Requires at least: 5.5.1
Tested up to: 6.4.1
Gutenberg compatible: Yes
Stable tag: 0.3.1
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==
Provides 3 shortcodes that may help with Gutenberg block development. 

- [contents] - displays the raw post content - may help you debug your blocks
- [content] - displays the website content summary
- [guts] - displays some information about WordPress and Gutenberg


The oik-block plugin was originally developed for education, demonstration, experimentation and estimation.


== Installation ==
1. Upload the contents of the oik-block plugin to the `/wp-content/plugins/oik-block' directory
1. Activate the oik-block plugin through the 'Plugins' menu in WordPress


== Frequently Asked Questions ==

Where can I find out more?

[github bobbingwide oik-block ]


Note: For Gutenberg blocks for oik-shortcodes see [github bobbingwide oik-blocks].


Yes, it's dependent upon a number of other plugins:

- oik - for shortcodes
- oik-bwtrace - for logic associated with hooked functions
- Gutenberg

and for the batch routine ( oik-block-opinions.php )

- oik-batch

== What's been disabled in v0.3.0 ==
oik-block no longer displays the Editor selection meta box. 
Therefore oik-block can no longer be used to help you form Gutenberg compatibility opinions.

The following functionality still exists. It's just not called.

- It contains logic to evaluate Gutenberg's compatibility with site contents.
- It forms opinions at multiple levels and applies these to help you decide which editor is the safest to use for the content and context.
- These opinions are displayed in the Preferred Editor meta box.


== Screenshots ==
1. 

== Upgrade Notice ==
= 0.3.1 =
Update for support for PHP 8.1 and PHP 8.2

= 0.3.0 =
No longer displays the Editor selection meta box.

= 0.2.0 = 
Upgrade to have the Preferred Editor meta box only displayed in the Classic editor.

= 0.1.0 = 
Update for improved [guts] shortcode. Still dependent upon oik

= 0.1.0-alpha-20190105 = 
Renamed [blocks] shortcode to [contents]. 

= 0.1.0-alpha=20181128 =
Updated to reflect the "Gut feel" role. No longer contains any Gutenberg blocks, which were moved to oik-blocks. 

= 0.0.0-alpha-20181118 = 
Version for cwiccer.com testing compatibility with oik plugins.

= 0.0.0-alpha-20181022 = 
Upgrade for Gutenberg v4.0.0 support.

= 0.0.0-alpha-20180409 =
Upgrade for two new blocks ( CSS and CSV ) and a fix to the Countdown block.

= 0.0.0-alpha-20180401 =
Provides 8 blocks and a batch routine to form opinions about content compatibility.

= 0.0.0 =
New plugin, available only from [github bobbingwide oik-block]

== Changelog ==
= 0.3.1 =
* Changed: Added a PHPUnit test
* Tested: With WordPress 6.4.1 and WordPress Multisite
* Tested: With PHP 8.2
* Tested: With PHPUnit 9.6 


= 0.3.0 =
* Deleted: Don't bother with Editor selection meta-box any more #37
* Changed: Support PHP 8.2: #38
* Tested: With WordPress 6.3.1 and WordPress Multisite
* Tested: With PHP 8.2
* Tested: With PHPUnit 9.6 

= 0.2.0 = 
* Changed: Set the oik_block_meta_box to only display in the Classic editor #18
* Changed: Partially update tests for PHPUnit 9. They don't all run correctly.
* Fixed: Only set post_content if post is set
* Fixed: Replace post_id by instance variable
* Changed: Reconcile shared library changes
* Fixed: Fix [guts] shortcode when Gutenberg is in development mode

= 0.1.0 = 
* Changed: Improved [guts] when running in GUTENBERG_DEVELOPMENT_MODE. Issue #30
* Changed: Alter check to see if the block should display anything on archives. Issue #31
* Changed: Update bwtrace shared library to v3.0.0
* Changed: Remove extra tbody

= 0.1.0-alpha-20190105 =
* Changed: Is not being able to upload my .zip files a known problem?, [github bobbingwide oik-block issue 32]
* Changed: [guts] - don't display undefined constants, [github bobbingwide oik-block issue 30]
* Changed: Rename [blocks] shortcode to [contents],[github bobbingwide oik-block issue 31]
* Fixed: [guts] shortcode may not be able to call bw_wp. Use $wp_version instead

= 0.1.0-alpha-20181128 = 
* Changed: Reduce dependency on oik's presence when displaying the Preferred editor meta box [github bobbingwide oik-block issue 27]
* Changed: [guts] - display WordPress and Gutenberg version information	[github bobbingwide oik-block issue 30]
* Changed: check for file existence before attempting to load it in oik_block_hook_checker::check_prerequisites [github bobbingwide oik-block issue 25]
* Deleted: Removed JavaScript and configuration files now implemented in oik-blocks [github bobbingwide oik-block issues 21]
* Tested: With Gutenberg 4.5.1
* Tested: With PHP 7.1 and 7.2
* Tested: With WordPress 5.0-RC1 
 
= 0.0.0-alpha-20181118 = 
* Added: Prototype generic dynamic shortcode block [github bobbingwide oik-block issue 16]
* Changed: Update GitHub Issue block for Gutenberg v4.0.0 [github bobbingwide oik-block issue 1]
* Changed: Update for Gutenberg 4.x and WordPress 5.0-betax [github bobbingwide oik-block issue 28]
* Changed: Update oik-nivo for Gutenberg 4.0.0 and higher [github bobbingwide oik-block issue 13]
* Changed: Update oik-person for Gutenberg v4.0.0 [github bobbingwide oik-block issue 7]
* Deleted: Delete Zac Gordon's examples [github bobbingwide oik-block issue 12]

= 0.0.0-alpha-20181022 =
* Changed: Update Countdown block for Gutenberg 4.0.0 [github bobbingwide oik-block issue 11]
* Changed: Update oik-csv for Gutenberg 4.0.0 [github bobbingwide oik-block issue 24]
* Changed: Update oik-css for Gutenberg 4.0.0 [github bobbingwide oik-block issue 23]
* Changed: Update blocks README for Gutenberg 4.0.0 [github bobbingwide oik-block issue 28]
* Changed: Improve hook checking logic where the hook is not invoked in Gutenberg [github bobbingwide oik-block issue 25]
* Changed: Adjust attached hook counts for replace_editor - removed trace hook functions
* Changed: Update compare-hooks README. Add using results, move other stuff to sub-directories  

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

