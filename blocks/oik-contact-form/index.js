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

/**
 * Register e
 */
export default registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
		'oik-block/contact-form', 
    {
        // Localize title using wp.i18n.__()
        title: __( 'Contact form' ),
				
				description: 'Displays a Contact form',

        // Category Options: common, formatting, layout, widgets, embed
        category: 'common',

        // Dashicons Options - https://goo.gl/aTM1DQ
        icon: 'forms',

        // Limit to 3 Keywords / Phrases
        keywords: [
            __( 'Contact' ),
						__( 'Form' ),
            __( 'oik' ),
        ],

        // Set for each piece of dynamic data used in your block
        attributes: {
				
          user: {
            type: 'string',
          },
					
        },
			
				edit: props=> {
					
          return (
					
            <div className={ props.className }>
							<p>This is where the Contact form will appear.</p>
            </div>
          );
        },
				
			save() {
				 // Rendering in PHP
					return null;
			},
    }
);

