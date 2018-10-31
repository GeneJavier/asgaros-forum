<?php

if (!defined('ABSPATH')) exit;

?>
<div class="wrap" id="af-options">
    <?php
    $title = __('Appearance', 'asgaros-forum');
    $titleUpdated = __('Appearance updated.', 'asgaros-forum');
    $this->render_admin_header($title, $titleUpdated);
    ?>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <div class="postbox-container">

                <form method="post">
                    <?php wp_nonce_field('asgaros_forum_save_appearance'); ?>

                    <div class="postbox">
                        <h2 class="hndle dashicons-before dashicons-admin-customizer"><?php _e('Appearance', 'asgaros-forum'); ?></h2>
                        <div class="inside">
                            <table>
                                <?php
                                $themes = $this->asgarosforum->appearance->get_themes();
                                if (count($themes) > 1) { ?>
                                    <tr>
                                        <th><label for="theme"><?php _e('Theme', 'asgaros-forum'); ?>:</label></th>
                                        <td>
                                            <select name="theme" id="theme">
                                                <?php foreach ($themes as $k => $v) {
                                                    echo '<option value="'.$k.'" '.selected($k, $this->asgarosforum->appearance->get_current_theme(), false).'>'.$v['name'].'</option>';
                                                } ?>
                                            </select>
                                        </td>
                                    </tr>
                                <?php
                                }
                                $themesOption = $this->asgarosforum->appearance->is_default_theme();
                                ?>
                                <tr class="custom-color-selector" <?php if (!$themesOption) { echo 'style="display: none;"'; } ?>>
                                    <th><label for="custom_font"><?php _e('Font:', 'asgaros-forum'); ?></label></th>
                                    <td><input class="regular-text" type="text" name="custom_font" id="custom_font" value="<?php echo esc_html(stripslashes($this->asgarosforum->appearance->options['custom_font'])); ?>"></td>
                                </tr>
                                <tr class="custom-color-selector" <?php if (!$themesOption) { echo 'style="display: none;"'; } ?>>
                                    <th><label for="custom_font_size"><?php _e('Font size:', 'asgaros-forum'); ?></label></th>
                                    <td><input class="regular-text" type="text" name="custom_font_size" id="custom_font_size" value="<?php echo esc_html(stripslashes($this->asgarosforum->appearance->options['custom_font_size'])); ?>"></td>
                                </tr>
                                <tr class="custom-color-selector" <?php if (!$themesOption) { echo 'style="display: none;"'; } ?>>
                                    <th><label for="custom_color"><?php _e('Forum color:', 'asgaros-forum'); ?></label></th>
                                    <td><input type="text" value="<?php echo stripslashes($this->asgarosforum->appearance->options['custom_color']); ?>" class="color-picker" name="custom_color" id="custom_color" data-default-color="<?php echo $this->asgarosforum->appearance->options_default['custom_color']; ?>"></td>
                                </tr>
                                <tr class="custom-color-selector" <?php if (!$themesOption) { echo 'style="display: none;"'; } ?>>
                                    <th><label for="custom_accent_color"><?php _e('Accent color:', 'asgaros-forum'); ?></label></th>
                                    <td><input type="text" value="<?php echo stripslashes($this->asgarosforum->appearance->options['custom_accent_color']); ?>" class="color-picker" name="custom_accent_color" id="custom_accent_color" data-default-color="<?php echo $this->asgarosforum->appearance->options_default['custom_accent_color']; ?>"></td>
                                </tr>
                                <tr class="custom-color-selector" <?php if (!$themesOption) { echo 'style="display: none;"'; } ?>>
                                    <th><label for="custom_text_color"><?php _e('Text color:', 'asgaros-forum'); ?></label></th>
                                    <td><input type="text" value="<?php echo stripslashes($this->asgarosforum->appearance->options['custom_text_color']); ?>" class="color-picker" name="custom_text_color" id="custom_text_color" data-default-color="<?php echo $this->asgarosforum->appearance->options_default['custom_text_color']; ?>"></td>
                                </tr>
                                <tr class="custom-color-selector" <?php if (!$themesOption) { echo 'style="display: none;"'; } ?>>
                                    <th><label for="custom_background_color"><?php _e('Background color:', 'asgaros-forum'); ?></label></th>
                                    <td><input type="text" value="<?php echo stripslashes($this->asgarosforum->appearance->options['custom_background_color']); ?>" class="color-picker" name="custom_background_color" id="custom_background_color" data-default-color="<?php echo $this->asgarosforum->appearance->options_default['custom_background_color']; ?>"></td>
                                </tr>
                                <tr class="custom-color-selector" <?php if (!$themesOption) { echo 'style="display: none;"'; } ?>>
                                    <th><label for="custom_border_color"><?php _e('Border color:', 'asgaros-forum'); ?></label></th>
                                    <td><input type="text" value="<?php echo stripslashes($this->asgarosforum->appearance->options['custom_border_color']); ?>" class="color-picker" name="custom_border_color" id="custom_border_color" data-default-color="<?php echo $this->asgarosforum->appearance->options_default['custom_border_color']; ?>"></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <input type="submit" name="af_appearance_submit" class="button button-primary" value="<?php _e('Save Appearance', 'asgaros-forum'); ?>">
                </form>

            </div>
        </div>
    </div>
</div>
