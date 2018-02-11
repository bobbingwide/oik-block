import './style.scss';
import './editor.scss';

//import Input from './input';
//import TextControl from '@wordpress/components-text

// Get just the __() localization function from wp.i18n
const { __ } = wp.i18n;
// Get registerBlockType and Editable from wp.blocks
const { registerBlockType, Editable } = wp.blocks;
// Set the h2 header for the block since it is reused
const blockHeader = <h3>{ __( 'GitHub Issue' ) }</h3>;

var TextControl = wp.blocks.InspectorControls.TextControl;

/**
 * Register example block
 */
export default registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
    'oik-block/github',
    {
        // Localize title using wp.i18n.__()
        title: __( 'GitHub Issue' ),
				
				description: 'Display a link to a GitHub issue',

        // Category Options: common, formatting, layout, widgets, embed
        category: 'common',

        // Dashicons Options - https://goo.gl/aTM1DQ
        icon: 'wordpress-alt',

        // Limit to 3 Keywords / Phrases
        keywords: [
            __( 'GitHub' ),
            __( 'Issue' ),
            __( 'Link' ),
        ],

        // Set for each piece of dynamic data used in your block
        attributes: {
          issue: {
            source: 'text',
            type: 'string',
            selector: 'div div',
          },
					bit: {
						source: 'text',
						type: 'string',
					},
					
        },

        edit: props => {
          const onChangeInput = ( event ) => {
            props.setAttributes( { issue: event.target.value } );
						bit = 'bit'; 
						props.setAttributes( { bit: bit } );
          };
					
					//const focus = ( focus ) => {
					 	//props.setAttributes( { issue: 'fred' } );
					//};
					
          return (
            <div className={ props.className }>
							{blockHeader}
							<TextControl 
								id="issue" 
								label="issue" 
								value={ props.attributes.bit }
								onChange={ onChangeInput }
								onFocus={ focus }
							/>
            </div>
          );
        },
        save: props => {
					// console.log( props );
					//var shortcode =  {props.attributes.issue} ;
					var lsb = '[';
					var rsb = ']'
          return (
            <div>
						{blockHeader}
						<div>{lsb}
						github wordpress gutenberg issue
						{props.attributes.bit}
						{rsb}
						</div>
            </div>
          );
        },
    },
);
