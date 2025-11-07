( function( wp ) {
    const { registerBlockType } = wp.blocks;
    const { TextControl, ToggleControl, PanelBody, PanelRow } = wp.components;
    const InspectorControls = (wp.blockEditor && wp.blockEditor.InspectorControls) || (wp.editor && wp.editor.InspectorControls);

    registerBlockType('anom/bar', {
        title: 'Anomaly Class Bar',
        icon: 'shield',
        category: 'widgets',
            attributes: {
            item: { type: 'string', default: '106' },
            levelNumber: { type: 'number', default: 3 },
            containment: { type: 'string', default: 'keter' },
            containmentIcon: { type: 'string', default: '' },
            riskIcon: { type: 'string', default: '' },
            disruption: { type: 'string', default: 'amida' },
            disruptionIcon: { type: 'string', default: '' },
            risk: { type: 'string', default: 'critical' },
            clear: { type: 'number', default: 3 },
            american: { type: 'boolean', default: false },

            /* Label attributes for translations */
            itemLabel: { type: 'string', default: 'Item#:' },
            containmentLabel: { type: 'string', default: 'Containment Class:' },
            secondaryLabel: { type: 'string', default: 'Secondary Class:' },
            disruptionLabel: { type: 'string', default: 'Disruption Class:' },
            riskLabel: { type: 'string', default: 'Risk Class:' },
            clear1Label: { type: 'string', default: 'Unrestricted' },
            clear2Label: { type: 'string', default: 'Restricted' },
            clear3Label: { type: 'string', default: 'Confidential' },
            clear4Label: { type: 'string', default: 'Secret' },
            clear5Label: { type: 'string', default: 'Top-Secret' },
            clear6Label: { type: 'string', default: 'Cosmic Top-Secret' }
        },
        edit: function( props ) {
            const { attributes, setAttributes } = props;
            return [
                wp.element.createElement( InspectorControls, null,
                    wp.element.createElement( PanelBody, { title: 'Settings', initialOpen: true },
                        wp.element.createElement( PanelRow, null, wp.element.createElement( TextControl, { label: 'Item #', value: attributes.item, onChange: ( val ) => setAttributes( { item: val } ) } ) ),
                        wp.element.createElement( PanelRow, null, wp.element.createElement( TextControl, { label: 'Containment', value: attributes.containment, onChange: ( val ) => setAttributes( { containment: val } ) } ) ),
                        wp.element.createElement( PanelRow, null, wp.element.createElement( TextControl, { label: 'Level number (1-6)', value: attributes.levelNumber, onChange: ( val ) => setAttributes( { levelNumber: parseInt(val) || 1 } ) } ) ),
                        wp.element.createElement( PanelRow, null, wp.element.createElement( TextControl, { label: 'Disruption', value: attributes.disruption, onChange: ( val ) => setAttributes( { disruption: val } ) } ) ),
                        wp.element.createElement( PanelRow, null, wp.element.createElement( TextControl, { label: 'Risk', value: attributes.risk, onChange: ( val ) => setAttributes( { risk: val } ) } ) ),
                        wp.element.createElement( PanelRow, null, wp.element.createElement( 'div', null,
                            wp.element.createElement( 'label', null, 'Per-block image overrides (optional):' ),
                            wp.element.createElement( wp.blockEditor && wp.blockEditor.MediaUpload || wp.editor && wp.editor.MediaUpload, {
                                onSelect: function( media ) { setAttributes( { containmentIcon: media && media.url ? media.url : '' } ); },
                                allowedTypes: [ 'image' ],
                                render: function( obj ) { return wp.element.createElement( 'div', null, wp.element.createElement( 'button', { className: 'button', onClick: obj.open }, 'Select containment image' ), attributes.containmentIcon ? wp.element.createElement( 'img', { src: attributes.containmentIcon, style: { maxWidth: '80px', display: 'block', marginTop: '8px' } } ) : null ); }
                            })
                        ) ),
                        wp.element.createElement( PanelRow, null, wp.element.createElement( 'div', null,
                            wp.element.createElement( wp.blockEditor && wp.blockEditor.MediaUpload || wp.editor && wp.editor.MediaUpload, {
                                onSelect: function( media ) { setAttributes( { disruptionIcon: media && media.url ? media.url : '' } ); },
                                allowedTypes: [ 'image' ],
                                render: function( obj ) { return wp.element.createElement( 'div', null, wp.element.createElement( 'button', { className: 'button', onClick: obj.open }, 'Select disruption image' ), attributes.disruptionIcon ? wp.element.createElement( 'img', { src: attributes.disruptionIcon, style: { maxWidth: '80px', display: 'block', marginTop: '8px' } } ) : null ); }
                            })
                        ) ),
                        wp.element.createElement( PanelRow, null, wp.element.createElement( 'div', null,
                            wp.element.createElement( wp.blockEditor && wp.blockEditor.MediaUpload || wp.editor && wp.editor.MediaUpload, {
                                onSelect: function( media ) { setAttributes( { riskIcon: media && media.url ? media.url : '' } ); },
                                allowedTypes: [ 'image' ],
                                render: function( obj ) { return wp.element.createElement( 'div', null, wp.element.createElement( 'button', { className: 'button', onClick: obj.open }, 'Select risk image' ), attributes.riskIcon ? wp.element.createElement( 'img', { src: attributes.riskIcon, style: { maxWidth: '80px', display: 'block', marginTop: '8px' } } ) : null ); }
                            })
                        ) ),
                        wp.element.createElement( PanelRow, null, wp.element.createElement( ToggleControl, { label: 'American stripes', checked: attributes.american, onChange: ( val ) => setAttributes( { american: val } ) } ) )
                    ),

                    wp.element.createElement( PanelBody, { title: 'Labels (translations)', initialOpen: false },
                        wp.element.createElement( PanelRow, null, wp.element.createElement( TextControl, { label: 'Item label', value: attributes.itemLabel, onChange: ( val ) => setAttributes( { itemLabel: val } ) } ) ),
                        wp.element.createElement( PanelRow, null, wp.element.createElement( TextControl, { label: 'Containment label', value: attributes.containmentLabel, onChange: ( val ) => setAttributes( { containmentLabel: val } ) } ) ),
                        wp.element.createElement( PanelRow, null, wp.element.createElement( TextControl, { label: 'Secondary label', value: attributes.secondaryLabel, onChange: ( val ) => setAttributes( { secondaryLabel: val } ) } ) ),
                        wp.element.createElement( PanelRow, null, wp.element.createElement( TextControl, { label: 'Disruption label', value: attributes.disruptionLabel, onChange: ( val ) => setAttributes( { disruptionLabel: val } ) } ) ),
                        wp.element.createElement( PanelRow, null, wp.element.createElement( TextControl, { label: 'Risk label', value: attributes.riskLabel, onChange: ( val ) => setAttributes( { riskLabel: val } ) } ) ),
                        wp.element.createElement( PanelRow, null, wp.element.createElement( TextControl, { label: 'Clear 1 label', value: attributes.clear1Label, onChange: ( val ) => setAttributes( { clear1Label: val } ) } ) ),
                        wp.element.createElement( PanelRow, null, wp.element.createElement( TextControl, { label: 'Clear 2 label', value: attributes.clear2Label, onChange: ( val ) => setAttributes( { clear2Label: val } ) } ) ),
                        wp.element.createElement( PanelRow, null, wp.element.createElement( TextControl, { label: 'Clear 3 label', value: attributes.clear3Label, onChange: ( val ) => setAttributes( { clear3Label: val } ) } ) ),
                        wp.element.createElement( PanelRow, null, wp.element.createElement( TextControl, { label: 'Clear 4 label', value: attributes.clear4Label, onChange: ( val ) => setAttributes( { clear4Label: val } ) } ) ),
                        wp.element.createElement( PanelRow, null, wp.element.createElement( TextControl, { label: 'Clear 5 label', value: attributes.clear5Label, onChange: ( val ) => setAttributes( { clear5Label: val } ) } ) ),
                        wp.element.createElement( PanelRow, null, wp.element.createElement( TextControl, { label: 'Clear 6 label', value: attributes.clear6Label, onChange: ( val ) => setAttributes( { clear6Label: val } ) } ) )
                    )
                ),
                wp.element.createElement( wp.serverSideRender, { block: 'anom/bar', attributes: attributes } )
            ];
        },
        save: function() {
            return null; // server side
        }
    } );
} )( window.wp );
