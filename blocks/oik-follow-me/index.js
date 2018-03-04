import './style.scss';
import './editor.scss';

//import Input from './input';
//import TextControl from '@wordpress/components-text

// Get just the __() localization function from wp.i18n
const { __ } = wp.i18n;
// Get registerBlockType and Editable from wp.blocks
const { 
	registerBlockType, 
	Editable,
  InspectorControls,
 } = wp.blocks;
	 
const {
  Toolbar,
  Button,
  Tooltip,
  PanelBody,
  PanelRow,
  FormToggle,
	TextControl,

} = wp.components;
// Set the header for the block since it is reused
//const blockHeader = <h3>{ __( 'Person' ) }</h3>;

//var TextControl = wp.blocks.InspectorControls.TextControl;


const blockAttributes = {
	user: {
		type: 'string',
		default: 'mick',
	},
	alt: {
		type: 'string',
		default: '0',
	},
	network: {
		type: 'string',
		default: 'Twitter',
	},
	
};



/**
 * Register e
 */
export default registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
    'oik-block/follow-me',
    {
        // Localize title using wp.i18n.__()
        title: __( 'Follow me' ),
				
				description: 'Displays Social media links',

        // Category Options: common, formatting, layout, widgets, embed
        category: 'common',

        // Dashicons Options - https://goo.gl/aTM1DQ
        icon: 'share',

        // Limit to 3 Keywords / Phrases
        keywords: [
            __( 'Follow' ),
            __( 'oik' ),
        ],

        // Set for each piece of dynamic data used in your block
        attributes: blockAttributes,
				
				supports: { html: false },

        edit: props => {
					
					const onChangeUser = ( event ) => {
						props.setAttributes( { user: event } );
					};
					
					const onChangeAlt = ( event ) => {
						props.setAttributes( { alt: event } );
					};
					
					const onChangeNetwork = ( event ) => {
						props.setAttributes( { network: event } );
					};
					
					//var atts = props.attributes;
					var children = [];
					//for (var key of Object.keys( atts )) {
					//	var value = atts[key];
					//	console.log( value );
					children.push( <TextControl label="User" value={props.attributes.user} id="hm001" instanceId="fm-user" onChange={onChangeUser}  /> );
									
					//}
					
					var atts = props.attributes;
					var chatts = [];		
					for (var key of Object.keys( atts )) {
						var value = atts[key];
						if ( value ) {
							chatts.push( " " + key + "=" + value );
						}
					}
					
          return [
						
  					!! props.focus && (
              <InspectorControls key="follow-me">
								<PanelBody key="pb">
								<TextControl label="User" value={props.attributes.user} id="hm001" instanceId="fm-user" onChange={onChangeUser}  />
								<TextControl label="Alt" value={props.attributes.alt} id="hm002" instanceId="fm-alt" onChange={onChangeAlt}  /> 
								<TextControl label="Network" value={props.attributes.network} id="hm003" instanceId="fm-network" onChange={onChangeNetwork}  /> 
								 </PanelBody>
              </InspectorControls>
  					),
					
					
            <div className={ props.className }>
						[bw_follow_me{chatts}]
						</div>
          ];
        },
        save: props => {
					// console.log( props );
					//var shortcode =  {props.attributes.issue} ;
					var lsb = '[';
					var rsb = ']';
					var user = props.attributes.user;
					
					var atts = props.attributes;
					var chatts = [];		
					for (var key of Object.keys( atts )) {
						var value = atts[key];
						if ( value ) {
							chatts.push( " " + key + "=" + value );
						}
					}
          return (
						<div>
						{lsb}
						bw_follow_me {chatts}
						{rsb}
						</div>
          );
        },
    },
);
