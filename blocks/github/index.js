/**
 * Implements GitHub Issue shortcode block
 * 
 * Uses [github] shortcode from oik-bob-bing-wide plugin
 *
 * @copyright (C) Copyright Bobbing Wide 2018
 * @author Herb Miller @bobbingwide
 */
import './style.scss';
import './editor.scss';

// Get just the __() localization function from wp.i18n
const { __ } = wp.i18n;
// Get registerBlockType wp.blocks
const { 
	registerBlockType, 
} = wp.blocks;
// Set the h2 header for the block since it is reused
const blockHeader = <h3>{ __( 'GitHub Issue' ) }</h3>;

const { 
	TextControl,
} = wp.components;

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
            __( 'GitHub Issue' ),
            __( 'Link' ),
						__( 'oik' ),
        ],

        // Set for each piece of dynamic data used in your block
        attributes: {
					shortcode: {
						type: 'string',
						default: 'github',
					},
					owner: {
						type: 'string',
						default: 'wordpress',
					}, 
					repo: {
						type: 'string',
						default: 'gutenberg',
					},
          issue: {
            type: 'string',
          },
					
        },

        edit: props => {
          const onChangeInput = ( event ) => {
            props.setAttributes( { issue: event } );
          };
					
					
					
					//const focus = ( focus ) => {
					 	//props.setAttributes( { issue: 'fred' } );
					//};
					
          return (
            <div className={ props.className }>
							{blockHeader}
							<TextControl 
								id="issue" 
								label="Issue" 
								value={ props.attributes.issue }
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
						github {props.attributes.owner} {props.attributes.repo} issue {props.attributes.issue} 
						{rsb}
						</div>
            </div>
          );
        },
    },
);
