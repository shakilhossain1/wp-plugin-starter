<?php

namespace Services;

class WordFilter
{
    public function __construct()
    {
        add_filter('the_content', [__CLASS__, 'filter']);
        add_action('admin_menu', [__CLASS__, 'adminPage']);
        add_action('admin_init', [__CLASS__, 'settings']);
    }

    public function settings()
    {
        add_settings_section('word-filter-section', null, null, 'word-filter-settings-page');

        // Word Filter Words
        add_settings_field(
            'wf_words',
            __('Type Your words', 'word-filter'),
            [__CLASS__, 'wordsField'],
            'word-filter-settings-page',
            'word-filter-section'
        );
        register_setting(
            'word-filter',
            'wf_words',
            ['sanitize_callback' => 'sanitize_text_field', 'default' => '']
        );
    }

    public function wordsField()
    {
        ?>
            <textarea name="wf_words" id="wcf_words" cols="40" rows="5"><?= get_option('wf_words', ''); ?></textarea>
            <p class="description">each word separate by comma( , )</p>
        <?php
    }

    public function adminPage()
    {
        add_menu_page(
            __('Word Filter', 'word-filter'),
            __('Word Filter', 'word-filter'),
            'manage_options',
            'word-filter-settings-page',
            [__CLASS__, 'adminView'],
            'dashicons-filter'
        );
    }

    public function adminView()
    {
        ?>
            <div class="wrap">
                <h3><?= __('Word Filter', 'word-filter'); ?></h3>
                <form action="options.php" method="POST">
                    <?php
                        settings_fields('word-filter');
                        do_settings_sections('word-filter-settings-page');
                        submit_button();
                    ?>
                </form>
            </div>
        <?php
    }

    public function filter($content)
    {
        global $post;

        foreach( get_option('wf_post_types') as $post_type) {
            if (is_single() && is_main_query() && $post->post_type == $post_type) {
                $excludeWords = explode(',', get_option('wf_words'));
                $excludeWords = array_map('trim', $excludeWords);

                foreach($excludeWords as $word) {
                    array_push($excludeWords, ucfirst($word));
                }

                return str_replace($excludeWords, get_option('wf_replaced_text'), $content);
            }
        }

        return $content;
    }
}