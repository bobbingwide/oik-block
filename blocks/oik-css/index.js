import './style.scss';
import './editor.scss';

// Get just the __() localization function from wp.i18n
const { __ } = wp.i18n;
// Get registerBlockType and Editable from wp.blocks
const { 
	registerBlockType, 
	Editable,
  InspectorControls,
	PlainText,
 } = wp.blocks;
	 
const {
  Toolbar,
  Button,
  Tooltip,
  PanelBody,
  PanelRow,
  FormToggle,
	TextControl,
	withInstanceId,

} = wp.components;


const RawHTML = wp.element.RawHTML;
// Set the header for the block since it is reused
//const blockHeader = <h3>{ __( 'Person' ) }</h3>;

//var TextControl = wp.blocks.InspectorControls.TextControl;

/**
 * Register the oik-block/css block
 * 
 * registerBlockType is a function which takes the name of the block to register
 * and an object that contains the properties of the block.
 * Some of these properties are objects and others are functions
 */
export default registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
		'oik-block/css', 
    {
        // Localize title using wp.i18n.__()
        title: __( 'CSS' ),
				
				description: 'Inline CSS',

        // Category Options: common, formatting, layout, widgets, embed
        category: 'layout',

        // Dashicons Options - https://goo.gl/aTM1DQ
        icon: 'admin-appearance',

        // Limit to 3 Keywords / Phrases
        keywords: [
            __( 'CSS' ),
            __( 'oik' ),
        ],

        // Set for each piece of dynamic data used in your block
        attributes: {
				
          css: {
            type: 'string',
            source: 'html',
          },
					
        },
				
		supports: {
			customClassName: false,
			className: false,
			html: false,
		},
			
		edit: withInstanceId(
			( { attributes, setAttributes, instanceId } ) => {
				const inputId = `blocks-shortcode-input-${ instanceId }`;
	
				return (
					<div className="wp-block-shortcode">
						<PlainText
							id={ inputId }
							value={ attributes.css }
							placeholder={ __( 'Write CSS' ) }
							onChange={ ( css ) => setAttributes( { css } ) }
						/>
					</div>
				);
			}
		),
				
			saver() {
				 // Rendering in PHP but we still need the content
					return ( 
					<p>div  color: re; </p>
					);
			},
		

		/**
		 * We intend to render this dynamically but we need the content created by the user
		 */
		save( { attributes } ) {
			return <RawHTML>{ attributes.css }</RawHTML>;
		},
	},
);

