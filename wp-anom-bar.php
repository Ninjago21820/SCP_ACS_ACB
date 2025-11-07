<?php
/**
 * Plugin Name: Anomaly Class Bar Block
 * Description: Fournit un bloc Gutenberg (server-side) pour afficher la bande "anomaly class" (HTML/CSS fourni).
 * Version: 0.1
 * Author: Generated
 */

defined( 'ABSPATH' ) || exit;

function acb_register_block() {
    // Register block from metadata (blocks/anomaly/block.json)
    if ( function_exists( 'register_block_type' ) ) {
        register_block_type( __DIR__ . '/blocks/anomaly', array(
            'render_callback' => 'acb_render_block'
        ) );
    }
}
add_action( 'init', 'acb_register_block' );

/* --- Admin: settings page to map images to class keys --- */
function acb_add_admin_menu() {
    add_menu_page( 'Anom Bar Settings', 'Anom Bar', 'manage_options', 'acb-settings', 'acb_render_settings_page' );
}
add_action( 'admin_menu', 'acb_add_admin_menu' );

function acb_enqueue_admin_assets( $hook ) {
    if ( $hook !== 'toplevel_page_acb-settings' ) {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script( 'acb-admin-js', plugin_dir_url( __FILE__ ) . 'admin/acb-admin.js', array( 'jquery' ), '0.1', true );
}
add_action( 'admin_enqueue_scripts', 'acb_enqueue_admin_assets' );

function acb_render_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    if ( isset( $_POST['acb_settings_nonce'] ) && wp_verify_nonce( $_POST['acb_settings_nonce'], 'acb_save_settings' ) ) {
        // Reset to defaults handler
        if ( isset( $_POST['acb_reset'] ) && '1' === $_POST['acb_reset'] ) {
            $defaults = array(
                'containment' => array(
                    'safe' => array( 'url' => '', 'label' => 'Safe' ),
                    'euclid' => array( 'url' => '', 'label' => 'Euclid' ),
                    'keter' => array( 'url' => '', 'label' => 'Keter' ),
                    'esoteric' => array( 'url' => '', 'label' => 'Esoteric' ),
                    'thaumiel' => array( 'url' => '', 'label' => 'Thaumiel' ),
                    'dark' => array( 'url' => '', 'label' => 'Dark' ),
                ),
                'risk' => array(
                    'notice' => array( 'url' => '', 'label' => 'Notice' ),
                    'caution' => array( 'url' => '', 'label' => 'Caution' ),
                    'warning' => array( 'url' => '', 'label' => 'Warning' ),
                    'critical' => array( 'url' => '', 'label' => 'Critical' ),
                ),
                'disruption' => array(
                    'amida' => array( 'url' => '', 'label' => 'Amida' ),
                    'amida2' => array( 'url' => '', 'label' => 'Amida2' ),
                    'amida3' => array( 'url' => '', 'label' => 'Amida3' ),
                ),
            );
            update_option( 'acb_image_mappings', $defaults );
            echo '<div class="updated"><p>Mappings reset to defaults.</p></div>';
        }

        // Process containment/risk/disruption mappings. We store arrays with url + label per key.
        $mappings = array( 'containment' => array(), 'risk' => array(), 'disruption' => array() );

        if ( isset( $_POST['containment_key'] ) && is_array( $_POST['containment_key'] ) ) {
            foreach ( $_POST['containment_key'] as $i => $key ) {
                $key = sanitize_text_field( $key );
                $id = isset( $_POST['containment_id'][ $i ] ) ? intval( $_POST['containment_id'][ $i ] ) : 0;
                $label = isset( $_POST['containment_label'][ $i ] ) ? sanitize_text_field( $_POST['containment_label'][ $i ] ) : '';
                if ( $key ) {
                    $url = $id ? wp_get_attachment_url( $id ) : '';
                    $mappings['containment'][ $key ] = array( 'url' => $url, 'label' => $label );
                }
            }
        }

        if ( isset( $_POST['risk_key'] ) && is_array( $_POST['risk_key'] ) ) {
            foreach ( $_POST['risk_key'] as $i => $key ) {
                $key = sanitize_text_field( $key );
                $id = isset( $_POST['risk_id'][ $i ] ) ? intval( $_POST['risk_id'][ $i ] ) : 0;
                $label = isset( $_POST['risk_label'][ $i ] ) ? sanitize_text_field( $_POST['risk_label'][ $i ] ) : '';
                if ( $key ) {
                    $url = $id ? wp_get_attachment_url( $id ) : '';
                    $mappings['risk'][ $key ] = array( 'url' => $url, 'label' => $label );
                }
            }
        }

        if ( isset( $_POST['disruption_key'] ) && is_array( $_POST['disruption_key'] ) ) {
            foreach ( $_POST['disruption_key'] as $i => $key ) {
                $key = sanitize_text_field( $key );
                $id = isset( $_POST['disruption_id'][ $i ] ) ? intval( $_POST['disruption_id'][ $i ] ) : 0;
                $label = isset( $_POST['disruption_label'][ $i ] ) ? sanitize_text_field( $_POST['disruption_label'][ $i ] ) : '';
                if ( $key ) {
                    $url = $id ? wp_get_attachment_url( $id ) : '';
                    $mappings['disruption'][ $key ] = array( 'url' => $url, 'label' => $label );
                }
            }
        }

        update_option( 'acb_image_mappings', $mappings );
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    $mappings = get_option( 'acb_image_mappings', array( 'containment' => array(), 'risk' => array(), 'disruption' => array() ) );

    ?>
    <div class="wrap">
        <h1>Anom Bar — Image mappings</h1>
        <form method="post">
            <?php wp_nonce_field( 'acb_save_settings', 'acb_settings_nonce' ); ?>

            <h2>Containment images</h2>
            <p>Associate a local image to a containment key (e.g. <code>esoteric</code>, <code>keter</code>).</p>
            <table id="containment-table" class="widefat">
                <thead><tr><th>Key</th><th>Label (display)</th><th>Preview</th><th>Image</th><th></th></tr></thead>
                <tbody>
                <?php
                $default_containment = array( 'safe', 'euclid', 'keter', 'esoteric', 'thaumiel', 'dark' );
                if ( ! empty( $mappings['containment'] ) ) :
                    foreach ( $mappings['containment'] as $key => $data ) :
                        $url = is_array( $data ) && isset( $data['url'] ) ? $data['url'] : ( is_string( $data ) ? $data : '' );
                        $label = is_array( $data ) && isset( $data['label'] ) ? $data['label'] : '';
                ?>
                    <tr>
                        <td><input type="text" name="containment_key[]" value="<?php echo esc_attr( $key ); ?>" /></td>
                        <td><input type="text" name="containment_label[]" class="acb-label-input" value="<?php echo esc_attr( $label ); ?>" /></td>
                        <td class="acb-preview-cell"><div class="acb-preview" style="display:flex;align-items:center;gap:.5rem;"><img src="<?php echo esc_url( $url ); ?>" style="max-width:64px;<?php echo empty( $url ) ? 'display:none;' : ''; ?>" /><div class="acb-preview-label"><?php echo esc_html( $label ); ?></div></div></td>
                        <td>
                            <input type="hidden" name="containment_id[]" class="acb-attachment-id" value="<?php echo esc_attr( attachment_url_to_postid( $url ) ); ?>" />
                            <img src="<?php echo esc_url( $url ); ?>" style="max-width:80px;<?php echo empty( $url ) ? 'display:none;' : ''; ?>" />
                            <button class="button acb-select-media" data-target="containment">Select</button>
                        </td>
                        <td><button class="button acb-remove-row">Remove</button></td>
                    </tr>
                <?php endforeach; else:
                    // Render sensible defaults to help user get started
                    foreach ( $default_containment as $key ) : ?>
                    <tr>
                        <td><input type="text" name="containment_key[]" value="<?php echo esc_attr( $key ); ?>" /></td>
                        <td><input type="text" name="containment_label[]" class="acb-label-input" value="<?php echo esc_attr( ucfirst( $key ) ); ?>" /></td>
                        <td class="acb-preview-cell"><div class="acb-preview" style="display:flex;align-items:center;gap:.5rem;"><img src="" style="max-width:64px;display:none;" /><div class="acb-preview-label"><?php echo esc_html( ucfirst( $key ) ); ?></div></div></td>
                        <td>
                            <input type="hidden" name="containment_id[]" class="acb-attachment-id" value="" />
                            <img src="" style="max-width:80px;display:none;" />
                            <button class="button acb-select-media" data-target="containment">Select</button>
                        </td>
                        <td><button class="button acb-remove-row">Remove</button></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
            <p><button id="add-containment" class="button">Add containment mapping</button></p>

            <h2>Risk images</h2>
            <table id="risk-table" class="widefat">
                <thead><tr><th>Key</th><th>Label (display)</th><th>Preview</th><th>Image</th><th></th></tr></thead>
                <tbody>
                <?php
                $default_risk = array( 'notice', 'caution', 'warning', 'critical' );
                if ( ! empty( $mappings['risk'] ) ) :
                    foreach ( $mappings['risk'] as $key => $data ) :
                        $url = is_array( $data ) && isset( $data['url'] ) ? $data['url'] : ( is_string( $data ) ? $data : '' );
                        $label = is_array( $data ) && isset( $data['label'] ) ? $data['label'] : '';
                ?>
                    <tr>
                        <td><input type="text" name="risk_key[]" value="<?php echo esc_attr( $key ); ?>" /></td>
                        <td><input type="text" name="risk_label[]" class="acb-label-input" value="<?php echo esc_attr( $label ); ?>" /></td>
                        <td class="acb-preview-cell"><div class="acb-preview" style="display:flex;align-items:center;gap:.5rem;"><img src="<?php echo esc_url( $url ); ?>" style="max-width:64px;<?php echo empty( $url ) ? 'display:none;' : ''; ?>" /><div class="acb-preview-label"><?php echo esc_html( $label ); ?></div></div></td>
                        <td>
                            <input type="hidden" name="risk_id[]" class="acb-attachment-id" value="<?php echo esc_attr( attachment_url_to_postid( $url ) ); ?>" />
                            <img src="<?php echo esc_url( $url ); ?>" style="max-width:80px;<?php echo empty( $url ) ? 'display:none;' : ''; ?>" />
                            <button class="button acb-select-media" data-target="risk">Select</button>
                        </td>
                        <td><button class="button acb-remove-row">Remove</button></td>
                    </tr>
                <?php endforeach; else:
                    foreach ( $default_risk as $key ) : ?>
                    <tr>
                        <td><input type="text" name="risk_key[]" value="<?php echo esc_attr( $key ); ?>" /></td>
                        <td><input type="text" name="risk_label[]" class="acb-label-input" value="<?php echo esc_attr( ucfirst( $key ) ); ?>" /></td>
                        <td class="acb-preview-cell"><div class="acb-preview" style="display:flex;align-items:center;gap:.5rem;"><img src="" style="max-width:64px;display:none;" /><div class="acb-preview-label"><?php echo esc_html( ucfirst( $key ) ); ?></div></div></td>
                        <td>
                            <input type="hidden" name="risk_id[]" class="acb-attachment-id" value="" />
                            <img src="" style="max-width:80px;display:none;" />
                            <button class="button acb-select-media" data-target="risk">Select</button>
                        </td>
                        <td><button class="button acb-remove-row">Remove</button></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
            <p><button id="add-risk" class="button">Add risk mapping</button></p>

            <h2>Disruption images</h2>
            <table id="disruption-table" class="widefat">
                <thead><tr><th>Key</th><th>Label (display)</th><th>Preview</th><th>Image</th><th></th></tr></thead>
                <tbody>
                <?php
                $default_disruption = array( 'amida', 'amida2', 'amida3' );
                if ( ! empty( $mappings['disruption'] ) ) :
                    foreach ( $mappings['disruption'] as $key => $data ) :
                        $url = is_array( $data ) && isset( $data['url'] ) ? $data['url'] : ( is_string( $data ) ? $data : '' );
                        $label = is_array( $data ) && isset( $data['label'] ) ? $data['label'] : '';
                ?>
                    <tr>
                        <td><input type="text" name="disruption_key[]" value="<?php echo esc_attr( $key ); ?>" /></td>
                        <td><input type="text" name="disruption_label[]" class="acb-label-input" value="<?php echo esc_attr( $label ); ?>" /></td>
                        <td class="acb-preview-cell"><div class="acb-preview" style="display:flex;align-items:center;gap:.5rem;"><img src="<?php echo esc_url( $url ); ?>" style="max-width:64px;<?php echo empty( $url ) ? 'display:none;' : ''; ?>" /><div class="acb-preview-label"><?php echo esc_html( $label ); ?></div></div></td>
                        <td>
                            <input type="hidden" name="disruption_id[]" class="acb-attachment-id" value="<?php echo esc_attr( attachment_url_to_postid( $url ) ); ?>" />
                            <img src="<?php echo esc_url( $url ); ?>" style="max-width:80px;<?php echo empty( $url ) ? 'display:none;' : ''; ?>" />
                            <button class="button acb-select-media" data-target="disruption">Select</button>
                        </td>
                        <td><button class="button acb-remove-row">Remove</button></td>
                    </tr>
                <?php endforeach; else:
                    foreach ( $default_disruption as $key ) : ?>
                    <tr>
                        <td><input type="text" name="disruption_key[]" value="<?php echo esc_attr( $key ); ?>" /></td>
                        <td><input type="text" name="disruption_label[]" class="acb-label-input" value="<?php echo esc_attr( ucfirst( $key ) ); ?>" /></td>
                        <td class="acb-preview-cell"><div class="acb-preview" style="display:flex;align-items:center;gap:.5rem;"><img src="" style="max-width:64px;display:none;" /><div class="acb-preview-label"><?php echo esc_html( ucfirst( $key ) ); ?></div></div></td>
                        <td>
                            <input type="hidden" name="disruption_id[]" class="acb-attachment-id" value="" />
                            <img src="" style="max-width:80px;display:none;" />
                            <button class="button acb-select-media" data-target="disruption">Select</button>
                        </td>
                        <td><button class="button acb-remove-row">Remove</button></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
            <p><button id="add-disruption" class="button">Add disruption mapping</button></p>

            <p>
                <input type="submit" class="button button-primary" value="Save mappings" />
                <button type="submit" name="acb_reset" value="1" class="button" onclick="return confirm('Reset mappings to plugin defaults? This will overwrite current mappings.');">Reset to defaults</button>
            </p>
        </form>
    </div>
    <?php
}

function acb_render_block( $attributes = array(), $content = '' ) {
    // prepare attributes with defaults
    $item = isset( $attributes['item'] ) ? sanitize_text_field( $attributes['item'] ) : '106';
    $level_number = isset( $attributes['levelNumber'] ) ? intval( $attributes['levelNumber'] ) : 3;
    $level = 'Level' . $level_number;
    $containment = isset( $attributes['containment'] ) ? sanitize_text_field( $attributes['containment'] ) : 'keter';
    $secondary = isset( $attributes['secondaryClass'] ) ? sanitize_text_field( $attributes['secondaryClass'] ) : '';
    // Note: per-block secondaryIcon field removed in favor of containment/risk/disruption overrides and admin mappings.
    $disruption = isset( $attributes['disruption'] ) ? sanitize_text_field( $attributes['disruption'] ) : 'amida';
    $risk = isset( $attributes['risk'] ) ? sanitize_text_field( $attributes['risk'] ) : 'critical';
    $clear = isset( $attributes['clear'] ) ? intval( $attributes['levelNumber'] ) : 3;
    $american = ! empty( $attributes['american'] ) ? 'american' : '';

    /* labels for translation (editable in the block settings) */
    // change default label to 'SCP-' per user request
    $item_label = isset( $attributes['itemLabel'] ) ? sanitize_text_field( $attributes['itemLabel'] ) : 'SCP-';
    $containment_label = isset( $attributes['containmentLabel'] ) ? sanitize_text_field( $attributes['containmentLabel'] ) : 'Containment Class:';
    $secondary_label = isset( $attributes['secondaryLabel'] ) ? sanitize_text_field( $attributes['secondaryLabel'] ) : 'Secondary Class:';
    $disruption_label = isset( $attributes['disruptionLabel'] ) ? sanitize_text_field( $attributes['disruptionLabel'] ) : 'Disruption Class:';
    $risk_label = isset( $attributes['riskLabel'] ) ? sanitize_text_field( $attributes['riskLabel'] ) : 'Risk Class:';

    $clear1_label = isset( $attributes['clear1Label'] ) ? sanitize_text_field( $attributes['clear1Label'] ) : 'Unrestricted';
    $clear2_label = isset( $attributes['clear2Label'] ) ? sanitize_text_field( $attributes['clear2Label'] ) : 'Restricted';
    $clear3_label = isset( $attributes['clear3Label'] ) ? sanitize_text_field( $attributes['clear3Label'] ) : 'Confidential';
    $clear4_label = isset( $attributes['clear4Label'] ) ? sanitize_text_field( $attributes['clear4Label'] ) : 'Secret';
    $clear5_label = isset( $attributes['clear5Label'] ) ? sanitize_text_field( $attributes['clear5Label'] ) : 'Top-Secret';
    $clear6_label = isset( $attributes['clear6Label'] ) ? sanitize_text_field( $attributes['clear6Label'] ) : 'Cosmic Top-Secret';


    $clear = max(1, min(6, $clear));

    $clear_class = 'clear-' . $clear;
    $level_class = 'level-' . max(1, min(6, $level_number));
    $classes = trim( sprintf( 'anom-bar-container item-%1$s %2$s %3$s %4$s %5$s %6$s %7$s', esc_attr( $item ), esc_attr( $clear_class ), esc_attr( $containment ), esc_attr( $secondary ), esc_attr( $disruption ), esc_attr( $risk ) . ' ' . esc_attr( $american ), esc_attr( $level_class ) ) );

    // enqueue the style for front-end
    wp_enqueue_style( 'acb-style', plugin_dir_url( __FILE__ ) . 'blocks/anomaly/style.css', array(), '0.1' );

    // Retrieve mappings for images from plugin settings
    $mappings = get_option( 'acb_image_mappings', array( 'containment' => array(), 'risk' => array(), 'disruption' => array() ) );

    // If containment is esoteric, set secondary class to thaumiel
    if ( 'esoteric' === $containment ) {
        $secondary = 'thaumiel';
    }

    // Per-block override icons (from editor MediaUpload) — if provided, they take precedence
    $containment_icon_attr = isset( $attributes['containmentIcon'] ) ? esc_url_raw( $attributes['containmentIcon'] ) : '';
    $risk_icon_attr = isset( $attributes['riskIcon'] ) ? esc_url_raw( $attributes['riskIcon'] ) : '';
    $disruption_icon_attr = isset( $attributes['disruptionIcon'] ) ? esc_url_raw( $attributes['disruptionIcon'] ) : '';

    // Determine mapped images (if any) and mapped display labels
    $containment_icon = '';
    $risk_icon = '';
    $disruption_icon = '';
    $containment_display = $containment;
    $risk_display = $risk;
    $disruption_display = $disruption;
    $secondary_display = $secondary;
    // Resolve containment mapping: support exact key, case-insensitive label, and fallback for esoteric->thaumiel
    if ( $containment_icon_attr ) {
        $containment_icon = $containment_icon_attr;
    } else {
        $found = false;
        // try exact key match
        if ( isset( $mappings['containment'][ $containment ] ) ) {
            $data = $mappings['containment'][ $containment ];
            $found = true;
        } else {
            // try case-insensitive key or label match
            foreach ( $mappings['containment'] as $k => $d ) {
                if ( strcasecmp( $k, $containment ) === 0 ) { $data = $d; $found = true; break; }
                if ( is_array( $d ) && isset( $d['label'] ) && strcasecmp( $d['label'], $containment ) === 0 ) { $data = $d; $found = true; break; }
            }
        }
        if ( ! $found && 'esoteric' === $containment && isset( $mappings['containment']['thaumiel'] ) ) {
            $data = $mappings['containment']['thaumiel'];
            $found = true;
        }
        if ( ! empty( $data ) ) {
            $containment_icon = is_array( $data ) && isset( $data['url'] ) ? $data['url'] : ( is_string( $data ) ? $data : '' );
            if ( is_array( $data ) && ! empty( $data['label'] ) ) {
                $containment_display = $data['label'];
            }
        }
    }
    // Resolve risk mapping with fallbacks (exact key, case-insensitive key/label)
    if ( $risk_icon_attr ) {
        $risk_icon = $risk_icon_attr;
    } else {
        $rdata = null; $rfound = false;
        if ( isset( $mappings['risk'][ $risk ] ) ) {
            $rdata = $mappings['risk'][ $risk ]; $rfound = true;
        } else {
            foreach ( $mappings['risk'] as $k => $d ) {
                if ( strcasecmp( $k, $risk ) === 0 ) { $rdata = $d; $rfound = true; break; }
                if ( is_array( $d ) && isset( $d['label'] ) && strcasecmp( $d['label'], $risk ) === 0 ) { $rdata = $d; $rfound = true; break; }
            }
        }
        if ( $rfound && ! empty( $rdata ) ) {
            $risk_icon = is_array( $rdata ) && isset( $rdata['url'] ) ? $rdata['url'] : ( is_string( $rdata ) ? $rdata : '' );
            if ( is_array( $rdata ) && ! empty( $rdata['label'] ) ) {
                $risk_display = $rdata['label'];
            }
        }
    }
    // Resolve disruption mapping with fallbacks (exact key, case-insensitive key/label)
    if ( $disruption_icon_attr ) {
        $disruption_icon = $disruption_icon_attr;
    } else {
        $ddata = null; $dfound = false;
        if ( isset( $mappings['disruption'][ $disruption ] ) ) {
            $ddata = $mappings['disruption'][ $disruption ]; $dfound = true;
        } else {
            foreach ( $mappings['disruption'] as $k => $d ) {
                if ( strcasecmp( $k, $disruption ) === 0 ) { $ddata = $d; $dfound = true; break; }
                if ( is_array( $d ) && isset( $d['label'] ) && strcasecmp( $d['label'], $disruption ) === 0 ) { $ddata = $d; $dfound = true; break; }
            }
        }
        if ( $dfound && ! empty( $ddata ) ) {
            $disruption_icon = is_array( $ddata ) && isset( $ddata['url'] ) ? $ddata['url'] : ( is_string( $ddata ) ? $ddata : '' );
            if ( is_array( $ddata ) && ! empty( $ddata['label'] ) ) {
                $disruption_display = $ddata['label'];
            }
        }
    }

    // secondary class display may come from containment mappings as well
    $secondary_icon = '';
    if ( $secondary && isset( $mappings['containment'][ $secondary ] ) ) {
        $s = $mappings['containment'][ $secondary ];
        if ( is_array( $s ) && ! empty( $s['label'] ) ) {
            $secondary_display = $s['label'];
        }
        if ( is_array( $s ) && ! empty( $s['url'] ) ) {
            $secondary_icon = $s['url'];
        } elseif ( is_string( $s ) && ! empty( $s ) ) {
            $secondary_icon = $s;
        }
    }

    // Build per-instance inline CSS rules if mapping images exist. We create a unique class for this instance.
    $instance_class = 'acb-instance-' . uniqid();
    $inline_css = '';
    if ( $containment_icon ) {
        // apply containment icon as background image on pseudo element; do not force a white background so base styling remains intact
        $inline_css .= '.' . $instance_class . ' .text-part > .main-class::before{background-image: url(' . esc_url( $containment_icon ) . '); background-repeat:no-repeat; background-position:center; background-size:75% 75%;}
';
    }
    if ( $risk_icon ) {
        $inline_css .= '.' . $instance_class . ' .text-part .risk-class::after{background-image: url(' . esc_url( $risk_icon ) . '); background-repeat:no-repeat; background-position:center; background-size:contain;}
';
    }
    if ( $disruption_icon ) {
        $inline_css .= '.' . $instance_class . ' .text-part .disrupt-class::after{background-image: url(' . esc_url( $disruption_icon ) . '); background-repeat:no-repeat; background-position:center; background-size:contain;}
';
    }

    // expose secondary icon as per-instance background-image for the expected slots
    if ( $secondary_icon ) {
    $inline_css .= '.' . $instance_class . ' .text-part > .main-class::after{background-image: url(' . esc_url( $secondary_icon ) . '); background-repeat:no-repeat; background-position:center; background-size:contain;}
';
    $inline_css .= '.' . $instance_class . ' .danger-diamond > .bottom-icon::before{background-image: url(' . esc_url( $secondary_icon ) . '); background-repeat:no-repeat; background-position:center; background-size:contain;}
';
    $inline_css .= '.' . $instance_class . ' .text-part .second-class::before{background-image: url(' . esc_url( $secondary_icon ) . '); background-repeat:no-repeat; background-position:center; background-size:contain;}
';
    }

    $html = '';
    if ( $inline_css ) {
        $html .= '<style type="text/css">' . $inline_css . '</style>';
    }

    $html .= '<div class="' . $classes . ' ' . $instance_class . '">';
    $html .= '<div class="anom-bar">';
    $html .= '<div class="top-box">';
    $html .= '<div class="top-left-box"><span class="item">' . esc_html( $item_label ) . '</span> <span class="number">' . esc_html( $item ) . '</span></div>';
    $html .= '<div class="top-center-box">';
    $html .= '<div class="bar-one"></div><div class="bar-two"></div><div class="bar-three"></div><div class="bar-four"></div><div class="bar-five"></div><div class="bar-six"></div>';
    $html .= '</div>';
    // determine clearance label text
    switch ( $clear ) {
        case 1:
            $clear_text = $clear1_label;
            break;
        case 2:
            $clear_text = $clear2_label;
            break;
        case 4:
            $clear_text = $clear4_label;
            break;
        case 5:
            $clear_text = $clear5_label;
            break;
        case 6:
            $clear_text = $clear6_label;
            break;
        case 3:
            $clear_text = $clear3_label;
            break;
    }

    $html .= '<div class="top-right-box"><div class="level">' . esc_html( $level ) . '</div><div class="clearance"><span class="clear-label">' . esc_html( $clear_text ) . '</span></div></div>';
    $html .= '</div>'; // top-box

    $html .= '<div class="bottom-box">';
    $html .= '<div class="text-part">';
    $html .= '<div class="main-class">';
    $html .= '<div class="contain-class"><div class="class-category">' . esc_html( $containment_label ) . '</div><div class="class-text">' . esc_html( $containment_display ) . '</div></div>';
    if ( $secondary ) {
        $html .= '<div class="second-class"><div class="class-category">' . esc_html( $secondary_label ) . '</div><div class="class-text">' . esc_html( $secondary_display ) . '</div></div>';
    }
    $html .= '</div>'; // main-class

    $html .= '<div class="disrupt-class"><div class="class-category">' . esc_html( $disruption_label ) . '</div><div class="class-text">' . esc_html( $disruption_display ) . '</div></div>';
    $html .= '<div class="risk-class"><div class="class-category">' . esc_html( $risk_label ) . '</div><div class="class-text">' . esc_html( $risk_display ) . '</div></div>';
    $html .= '</div>'; // text-part

    $html .= '<div class="diamond-part">';
    $html .= '<div class="danger-diamond">';
    $html .= '<div class="arrows"></div><div class="octagon"></div><div class="quadrants">';
    $html .= '<div class="top-quad"></div><div class="right-quad"></div><div class="left-quad"></div><div class="bottom-quad"></div>';
    $html .= '</div><div class="top-icon"></div><div class="right-icon"></div><div class="left-icon"></div><div class="bottom-icon"></div></div>';
    $html .= '</div>'; // diamond-part

    $html .= '</div>'; // bottom-box
    $html .= '</div>'; // anom-bar
    $html .= '</div>'; // container

    return $html;
}

// Optional: add shortcode for quick testing
function acb_shortcode( $atts = array() ) {
    $atts = wp_parse_args( $atts, array( 'item' => '106' ) );
    return acb_render_block( $atts );
}
add_shortcode( 'anomaly_bar', 'acb_shortcode' );
