Hook                  | Type   | Num args | Attached | Count | Changes
--------------------- | -----  | -------- | -------- | ----- | -------
admin_body_class      | filter |        1 | 1 0      | 1		 | add: 10 gutenberg_add_admin_body_class
admin_enqueue_scripts | action |        1 | 11 8     | 1	 	 | add: 5 gutenberg_register_scripts_and_styles, add: 10 gutenberg_common_scripts_and_styles, add: 10 gutenberg_editor_scripts_and_styles
admin_footer          | action |        1 | 1 4      | 1     | del: 10 _admin_notice_post_locked, del: 10 _local_storage_notice, del: 10 WP_Post_Comments_List_Table::_js_vars
admin_head            | action |        0 | 7 6      | 1     | add: 99 gutenberg_collect_meta_box_data
admin_init            | action |        0 | 39 38    | 1     | add: 10 gutenberg_redirect_demo
admin_menu            | action |        1 | 25 24    | 1     | add: 5 gutenberg_menu
admin_print_footer_scripts | action |   0 | 3 4      | 1     | del: 1 _WP_Editors::enqueue_scripts, add: 45 _WP_Editors::print_default_editor_scripts, del: 50 _WP_Editorss::editor.js
admin_print_scripts   | action |        0 | 1 2      | 1     | del: 10 print_emoji_detection_script
do_meta_boxes         | action |        3 | 1 0      | 3     | add: 1000 gutenberg_meta_box_save
get_edit_post_link    | filter |        3 | 1 0      | 1     | add: 10 gutenberg_revisions_link_to_editor
manage_page_columns   | filter |        1 | 0 1      | 1     | del: 9 WP_Post_Comments_List_Table::get_columns
manage_post_columns   | filter |        1 | 0 1      | 1     | del: 9 WP_Post_Comments_List_Table::get_columns
plugins_loaded        | action |        0 | 9 8      | 3 1   | add: 10 gutenberg_load_plugin_textdomain
replace_editor        | filter |        2 | 3 2      | 1     | add: 10 gutenberg_init
screen_options_show_screen | filter |   2 | 1 0      | 1     | add: 10 __return_false
user_can_richedit     | filter |        1 | 1 0      | 1 2   | add: 10 __return_true


																								 

