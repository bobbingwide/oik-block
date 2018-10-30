/**
 * Implements Nivo slider shortcode block
 * 
 * Uses [nivo] shortcode.
 *
 * @copyright (C) Copyright Bobbing Wide 2018
 * @author Herb Miller @bobbingwide
 */


import './style.scss';
import './editor.scss';


// Get just the __() localization function from wp.i18n
const { __ } = wp.i18n;

// Get registerBlockType from wp.blocks
const { 
	registerBlockType,
} = wp.blocks;

const { 
	Editable,
  InspectorControls,
 } = wp.editor;
	 
const {
  Toolbar,
  Button,
  Tooltip,
  PanelBody,
  PanelRow,
  FormToggle,
	TextControl,
	SelectControl,
} = wp.components;

const RawHTML = wp.element.RawHTML;
const Fragment = wp.element.Fragment;

import { map, partial } from 'lodash';


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
 * These are the different options for the Theme select list
 * 
 * But I don't know how map() works so it all appears to be arse about face.
 */
const themeOptions = 
{ default: "Default",
  bar: "Bar",
};




/**
 * Register 
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
					 * 
					 * key needs to be in [] otherwise it becomes a literal
					 *
					 */ 
					//onChange={ partial( handleChange, 'someKey' ) }

					function onChangeAttr( key, value ) {
						//var nextAttributes = {};
						//nextAttributes[ key ] = value;
						//setAttributes( nextAttributes );
						props.setAttributes( { [key] : value } );
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
						
  					
              <InspectorControls key="ic-nivo">
								<PanelBody key="pb-nivo">
									<TextControl label="Theme" value={props.attributes.theme} id="theme" onChange={onChangeTheme} />
									<TextControl label="IDs" value={props.attributes.id} onChange={onChangeId} />
									<TextControl label="Effect" value={props.attributes.effect} onChange={ partial( onChangeAttr, 'effect' )} /> 
									 
																	
									<SelectControl label="t2" value={props.attributes.theme}
										options={ map( themeOptions, ( key, label ) => ( { value: label, label: key } ) ) }
										onChange={partial( onChangeAttr, 'theme' )}
									/>
								</PanelBody>
              </InspectorControls>
  					,
					
					
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
