import './style.scss';
import './editor.scss';


// Get just the __() localization function from wp.i18n
const { __ } = wp.i18n;

// Get registerBlockType and Editable from wp.blocks
const { 
	registerBlockType, 
} = wp.blocks;

const { 
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

const RawHTML = wp.element.RawHTML;


/**
 * Attempt to find an easier way to define each attribute
 * which is a shortcode parameter
 */
const blockAttributes = {
	since: {
		type: 'string',
		default: '',
	},
	until: {
		type: 'string',
		default: '',
	},
	url: {
		type: 'string',
		default: '',
	},
	description: {
		type: 'string',
		default: '',
	},
	expirytext: {
		type: 'string',
		default: '',
	},
	format: {
		type: 'string',
		default: '',
	},
	
};



/**
 * Register the Countdown timer
 */
export default registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
    'oik-block/countdown',
    {
        // Localize title using wp.i18n.__()
        title: __( 'Countdown' ),
				
				description: 'Countdown timer',

        // Category Options: common, formatting, layout, widgets, embed
        category: 'common',

        // Dashicons Options - https://goo.gl/aTM1DQ
        icon: 'clock',

        // Limit to 3 Keywords / Phrases
        keywords: [
            __( 'Countdown' ),
						__( 'timer' ),
            __( 'oik' ),
        ],

        // Set for each piece of dynamic data used in your block
        attributes: blockAttributes,
				
				supports: { html: false },

        edit: props => {
					
					const onChangeSince = ( event ) => {
						props.setAttributes( { since: event } );
					};
					
					const onChangeUntil = ( event ) => {
						props.setAttributes( { until: event } );
					};
					
					
					const onChangeURL = ( event ) => {
						props.setAttributes( { url: event } );
					};
					
					const onChangeDescription = ( event ) => {
						props.setAttributes( { description: event } );
					};
					
					const onChangeExpiryText = ( event ) => {
						props.setAttributes( { expirytext: event } );
					};
					
					const onChangeFormat = ( event ) => {
						props.setAttributes( { format: event } );
					};
					
					// For the time being we'll show the generated shortcode.
					
					
					var atts = props.attributes;
					var chatts = '[bw_countdown'; 	
					for (var key of Object.keys( atts )) {
						var value = atts[key];
						if ( value ) {
							chatts = chatts + " " + key + "=\"" + value + '"';
						}
					}
					chatts = chatts + ']';
					
          return [
						
  					  <InspectorControls key="ic-countdown">
								<PanelBody key="pb-countdown">
								<TextControl label="Since" value={props.attributes.since} key="cd-since" onChange={onChangeSince} />
								<TextControl label="Until" value={props.attributes.until} key="cd-until" onChange={onChangeUntil} />
								<TextControl label="URL" value={props.attributes.url} key="cd-url" onChange={onChangeURL} />
                <TextControl label="Description" value={props.attributes.description} key="cd-desc" onChange={onChangeDescription} />
								<TextControl label="Expiry Text" value={props.attributes.expirytext} key="cd-expirytext" onChange={onChangeExpiryText}  /> 
								<TextControl label="Format" value={props.attributes.format} key="cd-format" onChange={onChangeFormat} /> 
								 </PanelBody>
              </InspectorControls>,
					
					
            <div className={ props.className } key="chatts">
						{chatts}
						</div>
          ];
        },
        save: props => {
					var lsb = '[';
					var rsb = ']';
					var atts = props.attributes;
					var chatts = '[bw_countdown'; 	
					for (var key of Object.keys( atts )) {
						var value = atts[key];
						if ( value ) {
							chatts = chatts + " " + key + "=\"" + value + '"';
						}
					}
					chatts = chatts + ']';
					
					
					//const createMarkup()
					
					//props.setAttributes( { content: chatts } );
					//console.log( chatts );
					//console.log( props.attributes.content );
          return( <RawHTML>{chatts}</RawHTML> );
					 
  
        },
    },
);
