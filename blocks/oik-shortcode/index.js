import './style.scss';
import './editor.scss';

// Get just the __() localization function from wp.i18n
const { __ } = wp.i18n;
// Get registerBlockType and Editable from wp.blocks
const { 
	registerBlockType,
} = wp.blocks;

const { 
	Editable,
	PlainText,
  AlignmentToolbar,
  BlockControls,
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
} = wp.components;

const {
	withInstanceId,
} = wp.compose;	

const Fragment = wp.element.Fragment;
const RawHTML = wp.element.RawHTML;
// Set the header for the block since it is reused
//const blockHeader = <h3>{ __( 'Person' ) }</h3>;

//var TextControl = wp.blocks.InspectorControls.TextControl;

/**
 * Register the oik-block/shortcode-block block
 * 
 * registerBlockType is a function which takes the name of the block to register
 * and an object that contains the properties of the block.
 * Some of these properties are objects and others are functions
 */
export default registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
		'oik-block/shortcode-block', 
    {
        // Localize title using wp.i18n.__()
        title: __( 'Shortcode block' ),
				
				description: 'Expands oik shortcodes',

        // Category Options: common, formatting, layout, widgets, embed
        category: 'layout',

        // Dashicons Options - https://goo.gl/aTM1DQ
        icon: 'shortcode',

        // Limit to 3 Keywords / Phrases
        keywords: [
            __( 'Shortcode' ),
            __( 'oik' ),
        ],

        // Set for each piece of dynamic data used in your block
				// The shortcode should be displayed as a select list 
				// with text override. a la? 
				
				// We can't set a default for the shortcode since the attribute is not created when it's the default value
				// This can probably be used to our advantage if we expect the default value to come from options.
				
        attributes: {
					shortcode: {
						type: 'string',
						default: '',
					},
					
					content: {
						type: 'string',
						default: '',
					},
				
					
        },
				
		supports: {
			customClassName: false,
			className: false,
			html: false,
		},
			
		edit: withInstanceId(
			( { attributes, setAttributes, instanceId, isSelected } ) => {
				const inputId = `blocks-shortcode-input-${ instanceId }`;
				
				
				const onChangeContent = ( value ) => {
					setAttributes( { content: value } );
				};
				
				const onChangeShortcode = ( value ) => {
					setAttributes( { shortcode: value } );
				};
				

				
	
				return [
				
  					  <InspectorControls>
								<PanelBody>
									<TextControl label="Shortcode" value={attributes.shortcode} onChange={onChangeShortcode} />
								</PanelBody>
              </InspectorControls>
									
						,
					<div className="wp-block-oik-block-shortcode wp-block-shortcode">
						<TextControl label="Shortcode" value={attributes.shortcode} onChange={onChangeShortcode} />
						<PlainText
							id={ inputId }
							value={ attributes.content }
							placeholder={ __( 'Enter your shortcode content' ) }
							onChange={onChangeContent}
						/>
					</div>
				 					
				];
			}
		),
				

		/**
		 * We intend to render this dynamically. The content created by the user
		 * is stored in the content attribute. 
		 * 
		 */
		save( { attributes } ) {
			return null;
		},
	},
);

