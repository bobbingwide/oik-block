import './style.scss';
import './editor.scss';

//import Input from './input';
//import TextControl from '@wordpress/components-text

// Get just the __() localization function from wp.i18n
const { __ } = wp.i18n;
// Get registerBlockType and Editable from wp.blocks
const { registerBlockType, Editable } = wp.blocks;
// Set the header for the block since it is reused
const blockHeader = <h3>{ __( 'Address' ) }</h3>;

//var TextControl = wp.blocks.InspectorControls.TextControl;

/**
 * Register e
 */
export default registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
    'oik-block/address',
    {
        // Localize title using wp.i18n.__()
        title: __( 'Address' ),
				
				description: 'Displays your address',

        // Category Options: common, formatting, layout, widgets, embed
        category: 'common',

        // Dashicons Options - https://goo.gl/aTM1DQ
        icon: 'building',

        // Limit to 3 Keywords / Phrases
        keywords: [
            __( 'Address' ),
            __( 'oik' ),
        ],

        // Set for each piece of dynamic data used in your block
        attributes: {
					
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
							<p>This is where the address will appear</p>
            </div>
          );
        },
        save: props => {
					// console.log( props );
					//var shortcode =  {props.attributes.issue} ;
					var lsb = '[';
					var rsb = ']';
          return (
            <div>
						{blockHeader}
						{lsb}
						bw_address
						{rsb}
            </div>
          );
        },
    },
);
