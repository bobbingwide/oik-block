
# Comparison of post-new.php ( post type 'post' )

/src/wp-admin/post-new.php,,1.958800,7.2.4,1258,3688,301,11,341,24,12,6,50,0.37934494018555,
/src/wp-json/wp/v2/categories?per_page=100&orderby=count&order=desc&_fields=id%2Cname%2Cparent,,0.934997,7.2.4,1258,3109,273,11,268,24,12,6,9,0.021793127059937,
/src/wp-json/wp/v2/tags?per_page=100&orderby=count&order=desc&_fields=id%2Cname&search=,,0.827353,7.2.4,1258,3109,273,11,268,24,12,6,9,0.022889614105225,
/src/wp-json/wp/v2/taxonomies/category?context=edit,,0.821588,7.2.4,1258,3109,273,11,268,24,12,6,6,0.015989065170288,
/src/wp-json/wp/v2/taxonomies/post_tag?context=edit,,0.739648,7.2.4,1258,3109,273,11,268,24,12,6,6,0.012681007385254,
/src/wp-json/wp/v2/users?context=edit&per_page=100,,0.859152,7.2.4,1258,3109,273,11,268,24,12,6,10,0.017743110656738,
																																																											
/src/wp-admin/post-new.php?post_type=post&classic-editor=1


php gethooknames.php C:\svn\wordpress-develop\src/bwtraces.loh.1525090961.488		 > data\post-new\gpostnew.names
php gethooknames.php C:\svn\wordpress-develop\src/bwtraces.rest.1525090968.749   > data\post-new\gcat.names
php gethooknames.php C:\svn\wordpress-develop\src/bwtraces.rest.1525090968.756   > data\post-new\gtags.names
php gethooknames.php C:\svn\wordpress-develop\src/bwtraces.rest.1525090968.762   > data\post-new\gtaxcat.names
php gethooknames.php C:\svn\wordpress-develop\src/bwtraces.rest.1525090968.78	   > data\post-new\gtaxtag.names
php gethooknames.php C:\svn\wordpress-develop\src/bwtraces.rest.1525090968.81    > data\post-new\gusers.names

php mergehooks.php data\post-new\gpostnew.names data\post-new\gcat.names data\post-new\temp1
php mergehooks.php data\post-new\temp1 data\post-new\gcat.names data\post-new\temp2
php mergehooks.php data\post-new\temp2 data\post-new\gtags.names data\post-new\temp3
php mergehooks.php data\post-new\temp3 data\post-new\gtaxcat.names data\post-new\temp4
php mergehooks.php data\post-new\temp4 data\post-new\gtaxtag.names data\post-new\temp5
php mergehooks.php data\post-new\temp5 data\post-new\gusers.names data\post-new\gpostnewall.names
 
 
php gethooknames.php C:\svn\wordpress-develop\src/bwtraces.loh.1525091069.402 > data\post-new\cpostnew.names

php 2fs.php data\post-new\gpostnewall.names data\post-new\cpostnew.names
  
