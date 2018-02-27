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
const blockHeader = <h3>{ __( 'Person' ) }</h3>;

//var TextControl = wp.blocks.InspectorControls.TextControl;

/**
 * Register e
 */
export default registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
    'oik-block/person',
    {
        // Localize title using wp.i18n.__()
        title: __( 'Person' ),
				
				description: 'Displays a person\'s information',

        // Category Options: common, formatting, layout, widgets, embed
        category: 'common',

        // Dashicons Options - https://goo.gl/aTM1DQ
        icon: 'admin-users',

        // Limit to 3 Keywords / Phrases
        keywords: [
            __( 'Person' ),
            __( 'oik' ),
        ],

        // Set for each piece of dynamic data used in your block
        attributes: {
				
          user: {
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
					const onChangeUser = ( event ) => {
						console.log( event );
						props.setAttributes( { user: event } );
					};
					
          return [
					
  					!! props.focus && (
              <InspectorControls key="inspector">
								<PanelBody key="pb">
								<PanelRow key="user">
									<TextControl label="User" 
											value={ props.attributes.user } 
											id="user"
											onChange={ onChangeUser }
									/>
								</PanelRow> 

								</PanelBody>


              </InspectorControls>
  					),
            <div className={ props.className }>
							{blockHeader}
							<p>This is where the person information for {props.attributes.user} will appear.</p>
            </div>
          ];
        },
        save: props => {
					// console.log( props );
					//var shortcode =  {props.attributes.issue} ;
					var lsb = '[';
					var rsb = ']';
					var user = props.attributes.user;
          return (
            <div>
						{blockHeader}
						{lsb}
						bw_user user={user} fields=name,bio
						{rsb}
						{lsb}
						bw_follow_me user={user}
						{rsb}
            </div>
          );
        },
    },
);
