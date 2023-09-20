<?php
# Add the settings menu for the plugin
function charity_plugin_settings_menu() {
    add_menu_page(
        'Charity Plugin Settings',
        'Charity Settings',
        'manage_options',
        'charity-plugin-settings',
        'charity_plugin_settings_page',
        'dashicons-heart',
        99
    );
}
add_action('admin_menu', 'charity_plugin_settings_menu');

# The content of the settings page
function charity_plugin_settings_page() {
    ?>
    <div class="wrap">
        <h2>Charity Plugin Settings</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('charity_plugin_settings');
            do_settings_sections('charity-plugin-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

# Register the plugins' settings
function charity_plugin_register_settings() {
    register_setting('charity_plugin_settings', 'charity_plugin_titles', 'sanitize_titles_callback');

    add_settings_section(
        'charity_plugin_titles_section',
        'Charity Titles',
        'charity_plugin_titles_section_callback',
        'charity-plugin-settings'
    );

    for ($i = 1; $i <= 3; $i++) {
        add_settings_field(
            "charity_title_$i",
            "Title $i",
            'charity_title_callback',
            'charity-plugin-settings',
            'charity_plugin_titles_section',
            array('label_for' => "charity_title_$i")
        );
    }
}
add_action('admin_init', 'charity_plugin_register_settings');

# Sanitize callback for the titles
function sanitize_titles_callback($input): array {
    $new_input = array();
    foreach($input as $key => $value) {
        if(isset( $value )) {
            $new_input[$key] = sanitize_text_field( $value );
        }
    }
    return $new_input;
}

# Callback for the titles section
function charity_plugin_titles_section_callback() {
    echo 'Enter the titles for the charity options below:';
}

# Callback for each title field
function charity_title_callback($args) {
    $options = get_option('charity_plugin_titles', array());
    $field_id = $args['label_for'];
    $value = $options[ $field_id ] ?? '';
    echo '<input type="text" id="' . esc_attr($field_id) . '" name="charity_plugin_titles[' . esc_attr($field_id) . ']" value="' . esc_attr($value) . '">';
}

