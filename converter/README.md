Notes about the files in the converter folder:

## Source code

File | Purpose
---- | ------------
2fs.php | For comparing hook summaries between Gutenberg and Classic editor
class-component-counter.php | Summarises uses of components
class-component.php	| Gathers information about a component
class-shortcode-converter.php |	Used by oik-block.php
counter.php |  Counts instances of plugins and themes in subdirectory installs
converter.php |	routine to create converter.csv - summarising shortcodes


## Target files
Files | Content
----- | ----------------
sites.csv | Summary information of subdirectory sites
sites-components.csv | 
components.csv | similar to sites-components.csv
estimate.csv | summary for shortcodes
hook-change.csv | Summary of changes to hooks invoked




## counter.php


Counts instances of plugins and themes in subdirectory installs
 
Produces output for two reports: 
- sites.csv - version,site,#plugins,#themes 
- components.csv -
- site-components.csv - same as components.csv

### sites.csv 

Summary information of subdirectory sites

```
WordPress version,site,#plugins,#themes
"4.9.4",cwiccer,45,3
"4.9.4",hm,57,17
"4.9.4",wordpress,229,108
"4.9.4",wp-pompey,28,6
```

Notes: 
- the `wordpress` subdirectory is where I store my 'master' code
- not all the plugins are activated
- obviously only one theme is active in single sites
- but more than one may be used in WPMS
- Sites need to be at 4.9 or higher before Gutenberg will work 



### components.csv	& sites-components.csv

Produced by the class-component-counter.php


```
Type,Component,Count,Author,Third Party,Tests,Site
plugins,akismet,85,Automattic,1,,. 
plugins,buddypress,6,,1,,. 
plugins,cookie-cat,46,bobbingwide,,1,. 
plugins,easy-digital-downloads,10,Pippin Williamson and Company,,1,. 
```

- Count indicates the number of times the component was detected, across all subdirectories
- Author is extracted from the main plugin file
- Third party is set if it's not one of my plugins
- Tests is set if the component has a tests folder. But the test coverage is not determined.
- The Site column indicates the first folder in which the component was found
- components.csv does not have the Site column



## converter.php

### estimate.csv - Summary for shortcodes


```
Shortcode,Desc,#parameters,Component
OIK,Spells out the OIK Information Kit backronym,0,oik
api,Simple API link,1,oik-shortcodes
apis,Link to API definitions,3,oik-shortcodes
artisteer,Styled form of Artisteer,0,oik-bob-bing-wide
```

- Summarises shortcodes registered by active plugins and the theme
- The number of parameters indicates the size/complexity of the shortcode
- After incorporation into estimate.ods I added columns to indicate shortcode type
- This was then used to create class-shortcode-converter.php
- Created in qw/wordpress  


### estimate.ods

Spreadsheet of all the ways used so far to attempt to estimate the work involved.
Plus other information used to determine "Gut feeling".


### hook-change.csv

Result of two file scan of `[hook]` shortcodes produced during tracing 
of edit requests using Gutenberg vs using the Classic editor.



```
G 597
C 622

...

Match,admin_bar_menu
Attached hooks changed,admin_body_class

Attached hooks changed,admin_enqueue_scripts

Added,admin_footer
Added,admin_footer-post.php
Added,admin_footer_text
Attached hooks changed,admin_head

Match,admin_head-post.php
Attached hooks changed,admin_init

Match,admin_memory_limit
Attached hooks changed,admin_menu

Match,admin_notices
Deleted,admin_post_thumbnail_html
...


Total Added,91
Total Changed,10
Total Deleted,116
Total Same,495
```

- G 597 and C 622 show the total count of different hook names invoked
- The Total records summarise the differences - Same means Matched.
- Added hooks are those performed in Gutenberg but not in the Classic editor
- Deleted hooks are those not invoked in Gutenberg - see below
- Matched hooks are where there is no change in the hook shortcodes
- Attached hooks changed indicate a change in the number of functions attached to the hook

This example was produced for edit requests against post 813 









