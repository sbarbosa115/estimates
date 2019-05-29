<?php wp_enqueue_media() ?>

<div class="wrap">
    <p><a href="/estimates/new" target="_blank">Click in here to create a new order</a></p>

    <table class="form-table">
        <tr>
            <th scope="row">Customer Logo:</th>
            <td>
                <div class="upload">
                    <img data-src="" src="" width="" height="" />
                    <div>
                        <button type="submit" class="upload_image_button button">Upload Image</button>
                        <button type="submit" class="remove_image_button button">&times;</button>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <form method="post" action="<?php echo admin_url('options.php'); ?>">
        <?php settings_fields( 'estimates-settings' ); ?>
        <?php do_settings_sections( 'estimates-settings' ); ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">Customer Name:</th>
                <td>
                    <input type="text" name="customer_name" value="<?php echo get_option( 'customer_name' ); ?>" style="width: 300px;"/>
                    <input type="hidden" name="customer_logo" id="customer_logo" value="<?php echo get_option( 'customer_logo'
                    );
                    ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Customer Email:</th>
                <td>
                    <input type="text" name="customer_email" value="<?php echo get_option( 'customer_email' ); ?>" style="width: 300px;"/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Customer Phone:</th>
                <td>
                    <input type="text" name="customer_phone" value="<?php echo get_option( 'customer_phone' ); ?>"
                           style="width: 300px;"/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Email to sent each  PDF order:</th>
                <td><input type="email" name="estimates_to_email" value="<?php echo get_option( 'estimates_to_email' ); ?>" style="width: 300px;"/></td>
            </tr>
            <tr valign="top">
                <th scope="row">Default sender:</th>
                <td><input type="email" name="estimates_from_email" value="<?php echo get_option( 'estimates_from_email' ); ?>" style="width: 300px;"/></td>
            </tr>
            <tr valign="top">
                <th scope="row">Copy to signature space:</th>
                <td><textarea name="estimate_bottom_copy" style="width: 300px;"><?php echo get_option( 'estimate_bottom_copy' ); ?></textarea></td>
            </tr>
            <tr valign="top">
                <th scope="row">List of products separated by comma, e.g (Product 1,Product 2):</th>
                <td><textarea name="estimate_products" style="width: 300px;"><?php echo get_option( 'estimate_products' ); ?></textarea></td>
            </tr>
        </table>
        <?php submit_button('Save Options'); ?>
    </form>
</div>
