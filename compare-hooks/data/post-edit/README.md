# Comparison of post.php?action=edit for post 813

## Files in data/post-edit


File       | Contents
---------- | -------------------------------------------
813.mrg    | Sorted output from 2fs
c813.names | hooks for classic-editor post 813
g813.names | hooks for Gutenberg editing post 813
gmedia815.names | hooks for loading image ID 815 into post 813's Image block
g813all.names | Merged hooks for Gutenberg editing post 813
c813.tree  | Manually extracted hooks for classic-editor post 813 - tree format
g813.tree  | Manually extracted hooks for Gutenberg editing post 813 - tree format
README.md  | this file


Notes:
- The original files were produced in qw/src on 2018/04/12 from the requests shown below.
- c813.names and c813.tree were created from the trace log file for `/src/wp-admin/post.php?post=813&action=edit&classic-editor=1`
- g813.names and g813.tree were created from the trace log file for `/src/wp-admin/post.php?post=813&action=edit`
- The tree files have not yet been processed.

Both Gutenberg and the Classic editor were activated when the editor was invoked.



The following extract from bwtrace.vt.20180412 shows the trace files that were generated for each transaction.

```
/src/wp-admin/plugins.php,,4.009640,7.1.16,1273,3563,295,10,328,24,10,6,26,0.26541113853455,C:\svn\wordpress-develop\src/bwtraces.loh.1523535962.059,452,fe80::205b:65ee:2dbf:66b9,4.008111,2018-04-12T12:26:06+00:00,Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML; like Gecko) Chrome/65.0.3325.181 Safari/537.36,GET
/src/wp-admin/plugins.php?plugin_status=active,,2.560288,7.1.16,1273,3563,295,10,328,24,10,6,21,0.1412661075592,C:\svn\wordpress-develop\src/bwtraces.loh.1523535972.097,224,fe80::205b:65ee:2dbf:66b9,2.558622,2018-04-12T12:26:14+00:00,Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML; like Gecko) Chrome/65.0.3325.181 Safari/537.36,GET
/src/wp-admin/edit.php,,2.975049,7.1.16,1273,3553,294,10,326,24,10,6,37,0.25303721427917,C:\svn\wordpress-develop\src/bwtraces.loh.1523535999.425,131,fe80::205b:65ee:2dbf:66b9,2.973290,2018-04-12T12:26:42+00:00,Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML; like Gecko) Chrome/65.0.3325.181 Safari/537.36,GET
/src/wp-admin/admin-ajax.php,heartbeat,1.207960,7.1.16,1273,3544,284,10,303,24,10,6,46,0.039180278778076,C:\svn\wordpress-develop\src/bwtraces.ajax.1523536004.001,52,fe80::205b:65ee:2dbf:66b9,1.206807,2018-04-12T12:26:45+00:00,Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML; like Gecko) Chrome/65.0.3325.181 Safari/537.36,POST
/src/wp-admin/admin-ajax.php,heartbeat,1.098092,7.1.16,1273,3544,284,10,303,24,10,6,46,0.038719177246094,C:\svn\wordpress-develop\src/bwtraces.ajax.1523536019.019,52,fe80::205b:65ee:2dbf:66b9,1.097427,2018-04-12T12:27:00+00:00,Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML; like Gecko) Chrome/65.0.3325.181 Safari/537.36,POST
/src/wp-admin/post.php?post=813&action=edit&classic-editor=1,,2.500817,7.1.16,1273,3626,295,10,335,24,10,6,43,0.30848622322083,C:\svn\wordpress-develop\src/bwtraces.loh.1523536021.8,142,fe80::205b:65ee:2dbf:66b9,2.499541,2018-04-12T12:27:04+00:00,Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML; like Gecko) Chrome/65.0.3325.181 Safari/537.36,GET
/src/?p=813,,1.112564,7.1.16,1273,3080,271,10,267,24,10,6,6,0.01434326171875,C:\svn\wordpress-develop\src/bwtraces.loh.1523536540.569,54,fe80::205b:65ee:2dbf:66b9,1.111071,2018-04-12T12:35:41+00:00,Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML; like Gecko) Chrome/65.0.3325.181 Safari/537.36,GET
/src/?p=813,,1.516817,7.1.16,1273,3139,279,10,291,24,10,6,23,0.051685810089111,C:\svn\wordpress-develop\src/bwtraces.loh.1523536541.778,112,fe80::205b:65ee:2dbf:66b9,1.515269,2018-04-12T12:35:43+00:00,Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML; like Gecko) Chrome/65.0.3325.181 Safari/537.36,GET
/src/wp-json/wp/v2/,,1.119172,7.1.16,1273,3080,271,10,266,24,10,6,6,0.012054204940796,C:\svn\wordpress-develop\src/bwtraces.rest.1523536545.628,58,fe80::205b:65ee:2dbf:66b9,1.118525,2018-04-12T12:35:46+00:00,Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML; like Gecko) Chrome/65.0.3325.181 Safari/537.36,GET
/src/wp-admin/post.php?post=813&action=edit,,2.094250,7.1.16,1273,3603,302,10,341,24,10,6,30,0.066166639328003,C:\svn\wordpress-develop\src/bwtraces.loh.1523536551.302,184,fe80::205b:65ee:2dbf:66b9,2.093060,2018-04-12T12:35:53+00:00,Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML; like Gecko) Chrome/65.0.3325.181 Safari/537.36,GET
/src/wp-admin/post.php?post=813&action=edit,,2.806357,7.1.16,1273,3645,303,10,346,24,10,6,42,0.15151524543762,C:\svn\wordpress-develop\src/bwtraces.loh.1523536660.281,197,fe80::205b:65ee:2dbf:66b9,2.804759,2018-04-12T12:37:43+00:00,Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML; like Gecko) Chrome/65.0.3325.181 Safari/537.36,GET
/src/wp-json/wp/v2/media/815,,1.298115,7.1.16,1273,3080,271,10,266,24,10,6,8,0.017548084259033,C:\svn\wordpress-develop\src/bwtraces.rest.1523536670.243,94,fe80::205b:65ee:2dbf:66b9,1.297185,2018-04-12T12:37:51+00:00,Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML; like Gecko) Chrome/65.0.3325.181 Safari/537.36,GET
/src/wp-admin/admin-ajax.php,heartbeat,1.015425,7.1.16,1273,3544,284,10,303,24,10,6,6,0.011444807052612,C:\svn\wordpress-develop\src/bwtraces.ajax.1523536731.647,52,fe80::205b:65ee:2dbf:66b9,1.014728,2018-04-12T12:38:52+00:00,Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML; like Gecko) Chrome/65.0.3325.181 Safari/537.36,POST
...
/src/wp-admin/admin-ajax.php,heartbeat,1.047046,7.1.16,1273,3544,284,10,303,24,10,6,6,0.011185884475708,C:\svn\wordpress-develop\src/bwtraces.ajax.1523538314.96,52,fe80::205b:65ee:2dbf:66b9,1.046146,2018-04-12T13:05:16+00:00,Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML; like Gecko) Chrome/65.0.3325.181 Safari/537.36,POST
```




### Semi automating analysis for post.php?action=edit


```
cd \apache\htdocs\wordpress\wp-content\plugins\oik-block
cd compare-hooks
rem Extract [hook] shortcodes

php gethooknames.php C:\svn\wordpress-develop\src/bwtraces.loh.1523536021.8	> data\post-edit\c813.names

php gethooknames.php C:\svn\wordpress-develop\src/bwtraces.loh.1523536660.281 > data\post-edit\g813.names
php gethooknames.php C:\svn\wordpress-develop\src/bwtraces.rest.1523536670.243 > data\post-edit\gmedia815.names

rem Merge output from multiple Gutenberg requests
php mergehooks.php data\post-edit\g813.names data\post-edit\gmedia815.names data\post-edit\g813all.names

rem Two file scan of the resulting files
php 2fs.php data\post-edit\g813all.names data\post-edit\c813.names > data\post-edit\813.mrg

```

# Re-editing 30 April

Subsequent editing of the post, with meta boxes open, recorded additional REST requests.
These have not been included in the analysis. 

```
/src/wp-json/wp/v2/taxonomies/category?context=edit,,0.497560,7.2.4,1258,3109,273,11,268,24,12,6,6,0.013514041900635,C:\svn\wordpress-develop\src/bwtraces.rest.1525100400.714,57,192.168.50.1,0
.496614,2018-04-30T15:00:01+00:00,Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML; like Gecko) Chrome/66.0.3359.139 Safari/537.36,GET
/src/wp-json/wp/v2/categories?per_page=100&orderby=count&order=desc&_fields=id%2Cname%2Cparent,,0.544206,7.2.4,1258,3109,273,11,268,24,12,6,9,0.016856908798218,C:\svn\wordpress-develop\src/bwt
races.rest.1525100400.699,57,192.168.50.1,0.542890,2018-04-30T15:00:01+00:00,Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML; like Gecko) Chrome/66.0.3359.139 Safari/537.36
,GET
/src/wp-json/wp/v2/taxonomies/post_tag?context=edit,,0.709681,7.2.4,1258,3109,273,11,268,24,12,6,6,0.014302730560303,C:\svn\wordpress-develop\src/bwtraces.rest.1525100400.745,57,192.168.50.1,0
.708280,2018-04-30T15:00:01+00:00,Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML; like Gecko) Chrome/66.0.3359.139 Safari/537.36,GET
/src/wp-json/wp/v2/tags?per_page=100&orderby=count&order=desc&_fields=id%2Cname&search=,,0.774330,7.2.4,1258,3109,273,11,268,24,12,6,9,0.019623041152954,C:\svn\wordpress-develop\src/bwtraces.r
est.1525100400.729,57,192.168.50.1,0.773328,2018-04-30T15:00:01+00:00,Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML; like Gecko) Chrome/66.0.3359.139 Safari/537.36,GET
```


