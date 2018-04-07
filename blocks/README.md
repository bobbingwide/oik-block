# oik blocks


## Build folder

The `build` folder contains the run-time components:
Folder | File | Contents
------ | ----- | -------
css    | blocks.editor.css | Styles used in the Block editor
css    | blocks.style.css | Styles used in the front end
images | js-wapuu.svg | wapuu SVG file - from Zac's course
js     | dummy.blocks.js | Dummy editor.blocks.js for testing Issue #4678
js     | editor.blocks.js | Built JS for the Editor
js     | frontend.blocks.js | Built JS for the front end



## Summary of blocks

`index.js` defines the blocks that are built for this plugin.

The `blocks` folder contains the source for the Editor blocks.

Block | Shortcode(s) | Purpose
----- | -------- | -----
github | [github] | Wrapper to [github] shortcode
oik-address | [bw_address] | Address block
oik-contact-form | [bw_contact_form] | Contact form 
oik-countdown |	[bw_countdown] | Countdown timer
oik-follow-me | [bw_follow_me] | Social media follow me
oik-googlemap | [bw_show_googlemap] | Google Maps Map
oik-nivo | [nivo] | Nivo slider
oik-person | [bw_user] [bw_follow_me] | Person block
oik-css | [bw_css] | Inline CSS 
oik-csv | [bw_csv] | Display CSV content

In each folder there are at least 3 files

File |  Target | Contents
----- | ------	| --------------
editor.scss | blocks.editor.css | Styling for the editor
index.js | editor.blocks.js | REACT JS for the block
style.scss | blocks.style.css | Styling for the front end

## Build process

To build the JavaScript code in the blocks folder you need to use npm (Node Package Manager).
First you must install npm.

npm install


npm run dev




 
