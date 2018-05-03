### Requirement

Determine how hook invocation changes when using Gutenberg versus the Classic editor
to see if that helps us in automatic detection of plugins that may work differently...
leading to there being incompatibilities. 

We're hoping for peaceful coexistence.

The new block editor performs its initial request using the REST API functionality 
then there are multiple further REST requests to obtain the data for meta boxes.

Any plugin that uses one of the hooks that has somehow changed could be affected.



### Proposed solution


Our process of comparing hooks involves

- Tracing Classic editor and Gutenberg requests
- Extracting [hook] shortcodes ( using gethooknames.php )
- Merging output from multiple requests ( using mergehooks.php )
- Two file scan of the resulting files ( using 2fs.php )

The two file scan's output is a summary report of changed hooks
which can be used when analysing the plugins.

We need to merge the output from multiple Gutenberg requests in order to perform a fair comparison
with the hooks invoked by the Classic editor.

 
The theory is that we need to merge the subsequent REST requests that are performed to populate the Meta boxes.
For the time being we'll ignore the requests performed by dynamic blocks, since existing content isn't expected to contain these.


Request                     | Gutenberg Hooks | Classic hooks | Added | Changed | Deleted | Same
--------------              | --------------- | ------------- | ----- | ------- | ------- | ----
post.php?action=edit      	| 626             | 621           |   117 |      10 |     112 | 499
post-new.php                | 684             | 659           |    92 |      16 |      67 | 576
post-new.php?post_type=page | 705             | 640           |   131 |      15 |      66 | 599


Notes: 
- If we just look at the edit request the number of changes to comprehend appears unmanageable.
- Merging in the hooks from the subsequent REST requests doesn't appear to make much difference to the overall figures. 
- In a nutshell, there's quite a bit of difference.

### Using the results

Having determined the differences we can use the results to create logic to detect use of each of the affected hooks.
Can we perform this detection in the meta box?
Perhaps not if the filter being invoked but
we can probably tell if there is an attached hook. 
So put into a spreadsheet and count them.


Type   | Change  | Comments
------ | ------- | ------------------------
action | Added	 | Should not be a problem unless the plugin implements the hook already
filter | Added   | Should not be a problem unless the plugin implements the hook already
action | Changed num args | NO NO
action | Changed attached hooks | need to know which Gutenberg function
filter | Changed num args | NO NO
filter | Changed attached hooks | need to know which Gutenberg function
action | Deleted | A problem if the plugin responds to the hook  
filter | Deleted | Could be a problem if the plugin responded to the hook




### Routines


File             | Purpose
---------------- | -------
gethooknames.php | Extracts [hook] records from relevant trace files
mergehooks.php   | Merges two .names files to create an accumulated .names file
2fs.php          | Performs two file scan comparing a Gutenberg .names file with Classic .names file


### Data files

File           | Rows | Contents			
-----          | ---- | -----------
data/hook-change.md |  214 | Summarises changed hooks for post-new.php?post_type=page 
attached_hooks_changed.md |  | Summarises the changes to attached hooks - added and deleted functions with priority


Subdirectories, each with their own README.md file

- post-edit	- see post.php?action=edit 
- post-new  - see post-new.php  
- post-new-page - see post-new.php?post_type=page



### References


- https://github.com/WordPress/gutenberg/issues/1316
- https://github.com/WordPress/gutenberg/issues/4151 Document WordPress classic editor integration points and Gutenberg equivalents

https://github.com/danielbachhuber/gutenberg-migration-guide


And \apache\htdocs\hm\issue-x 
where I compared add-new

vs these files where it's edit



