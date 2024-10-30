<div class="settings-container">
    <?php  
    $app_settings_url = isset($_POST['is_app_settings_url']) ? esc_url_raw($_POST['is_app_settings_url']) : '';
    if (!$app_settings_url) {
        $app_settings_url = get_option('is_app_settings_url', ''); // Providing a default value if the option doesn't exist
    }
    
    $api_settings_url = isset($_POST['is_api_settings_url']) ? esc_url_raw($_POST['is_api_settings_url']) : '';
    if (!$api_settings_url) {
        $api_settings_url = get_option('is_api_settings_url', ''); // Providing a default value if the option doesn't exist
    }
    // Output the form with the text field
    echo '<form method="post">';
    echo '<label for="settings_url">End Points</label>';
    echo '<input type="url" id="is_app_settings_url" name="is_app_settings_url" value="' . esc_attr($app_settings_url) . '"  placeholder="https://app.intellasphere.com/">';
    echo '<input type="url" id="is_api_settings_url" name="is_api_settings_url" value="' . esc_attr($api_settings_url) . '" placeholder="https://api.intellasphere.com/">';
    echo '<input type="submit" name="save_settings_field" value="Submit">';
    echo '</form>';

    if (isset($_POST['save_settings_field'])) {
        //delete_option('is_connect_email');
        //delete_option('is_op_array');
        // Sanitize and validate the URL input
        $app_field_value = esc_url_raw($_POST['is_app_settings_url']);
        if ($app_field_value) {
            update_option('is_app_settings_url', $app_field_value);
        } else {
            echo '<p style="color: red;">Invalid URL entered. Please enter a valid URL.</p>';
        }

        $api_field_value = esc_url_raw($_POST['is_api_settings_url']);
        if ($api_field_value) {
            update_option('is_api_settings_url', $api_field_value);
        } else {
            echo '<p style="color: red;">Invalid URL entered. Please enter a valid URL.</p>';
        }
    } 
    ?>

<style>
    .settings-container {
        max-width: 400px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    .settings-container label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .settings-container input[type="url"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        margin-bottom: 15px;
    }

    .settings-container input[type="submit"] {
        background-color: #0090ef;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .settings-container input[type="submit"]:hover {
        background-color: #0090ef;
    }

    .settings-container p.error-message {
        color: red;
        margin-top: 5px;
    }
</style>
</div> 
