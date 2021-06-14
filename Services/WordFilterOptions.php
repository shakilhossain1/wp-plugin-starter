<?php

namespace Services;

class WordFilterOptions
{
    public function __construct()
    {
        add_action('admin_menu', [__CLASS__, 'adminMenu']);
        add_action('admin_init', [__CLASS__, 'settings']);
    }

    public function settings()
    {
        add_settings_section('word-filter-options', null, null, 'word-filter-options-page');

        // Word Filter Replaced text
        add_settings_field(
            'wf_replaced_text',
            __('Replaced Text', 'word-filter'),
            [__CLASS__, 'textField'],
            'word-filter-options-page',
            'word-filter-options'
        );
        register_setting(
            'word-filter-options',
            'wf_replaced_text',
            ['sanitize_callback' => 'sanitize_text_field','default' => '']
        );

        // Word Filter Post Types
        add_settings_field(
            'wf_post_types',
            __('Where You Want to Exclue Words', 'word-filter'),
            [__CLASS__, 'selectField'],
            'word-filter-options-page',
            'word-filter-options'
        );
        register_setting(
            'word-filter-options',
            'wf_post_types',
            ['sanitize_callback' => [__CLASS__, 'sanitizePostTypes'],'default' => ['post', 'professor']]
        );
    }

    public function sanitizePostTypes($value)
    {
        return $value;
    }

    public function selectField()
    {
        $excludePostTypes = ['attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset', 'oembed_cache', 'user_request', 'wp_block', 'acf-field-group', 'acf-field'];
        $post_types = array_diff(get_post_types(), $excludePostTypes);
        $active = array_fill_keys(get_option('wf_post_types'), 1);
        ?>
            <select class="widefat" name="wf_post_types[]" multiple id="wf_post_types">
                <?php foreach($post_types as $post) : ?>
                    <option value="<?= $post ?>" <?php selected($active[$post], 1); ?>><?= $post ?></option>
                <?php endforeach; ?>
            </select>
        <?php
    }

    public function textField()
    {
        ?>
            <input type="text" name="wf_replaced_text" value="<?= esc_attr(get_option('wf_replaced_text')); ?>">
        <?php
    }

    public function adminMenu()
    {
        add_submenu_page(
            'word-filter-settings-page',
            __('Word filter Options', 'word-filter'),
            __('Options', 'word-filter'),
            'manage_options',
            'word-filter-options-page',
            [__CLASS__, 'adminHtml']
        );
    }

    public function adminHtml()
    {
        ?>
            <div class="wrap">
                <h2><?= __('Word Filter Options', 'word-filter'); ?></h2>
                <form action="options.php" method="POST">
                    <?php
                        settings_fields('word-filter-options');
                        do_settings_sections('word-filter-options-page');
                        submit_button();
                    ?>
                </form>
            </div>
        <?php
    }
}