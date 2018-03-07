import './style.scss';
import './editor.scss';


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

const RawHTML = wp.element.RawHTML;
const Fragment = wp.element.Fragment;

import { partial } from 'lodash';


/**
 * Attempt to find an easier way to define each attribute
 * which is a shortcode parameter
                 , "theme" => BW_::bw_skv( "default", "bar|custom|dark|light|orman|pascal|oik271|default271", __( "Theme for the slideshow", "oik-nivo-slider" ) )
 */
const blockAttributes = {
	theme: {
		type: 'string',
		default: 'default',
	},
	id: {
		type: 'string',
		default: '',
	},
	effect: {
		type: 'string',
		default: '',
	},
	
};



/**
 * Register e
 */
export default registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
    'oik-block/nivo',
    {
        // Localize title using wp.i18n.__()
        title: __( 'Nivo slider' ),
				
				description: 'Nivo slider',

        // Category Options: common, formatting, layout, widgets, embed
        category: 'common',

        // Dashicons Options - https://goo.gl/aTM1DQ
        icon: 'slides',

        // Limit to 3 Keywords / Phrases
        keywords: [
            __( 'Nivo' ),
						__( 'slider' ),
            __( 'oik' ),
        ],

        // Set for each piece of dynamic data used in your block
        attributes: blockAttributes,
				
				supports: { html: false },

        edit: props => {
				
				
					const onChangeTheme = ( event ) => {
						props.setAttributes( { theme: event } );
					};
					
					const onChangeId = ( event ) => {
						props.setAttributes( { id: event } );
					};
					
					/**
					 * Attempt a generic function to apply a change
					 * using the partial technique
					 */ 
					//onChange={ partial( handleChange, 'someKey' ) }

					function onChangeAttr( key, value ) {
						//var nextAttributes = {};
						//nextAttributes[ key ] = value;
						//setAttributes( nextAttributes );
						props.setAttributes( { key : value } );
					};
					
					
				
					var atts = props.attributes;
					var chatts = '[nivo'; 	
					for (var key of Object.keys( atts )) {
						var value = atts[key];
						if ( value ) {
							chatts = chatts + " " + key + "=\"" + value + '"';
						}
					}
					chatts = chatts + ']';
					
          return [
						
  					!! props.focus && (
              <InspectorControls key="ic-nivo">
								<PanelBody key="pb-nivo">
								<TextControl label="Theme" value={props.attributes.theme} id="theme" onChange={onChangeTheme} />
								<TextControl label="IDs" value={props.attributes.id} onChange={onChangeId} />
								<TextControl label="Effect" value={props.attributes.effect} onChange={ partial( onChangeAttr, 'effect' )} />
								 </PanelBody>
              </InspectorControls>
  					),
					
					
            <div className={ props.className } key="chatts">
						<Fragment>{chatts}</Fragment>
						</div>
          ];
        },
        save: props => {
					var lsb = '[';
					var rsb = ']';
					var atts = props.attributes;
					var chatts = '[nivo'; 	
					for (var key of Object.keys( atts )) {
						var value = atts[key];
						if ( value ) {
							chatts = chatts + " " + key + "=\"" + value + '"';
						}
					}
					chatts = chatts + ']';
					
					
					//const createMarkup()
					
					//props.setAttributes( { content: chatts } );
					console.log( chatts );
					//console.log( props.attributes.content );
          return( <RawHTML>{chatts}</RawHTML> );
					 
  
        },
    },
);
