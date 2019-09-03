<?php return [
    'plugin' => [
        'name' => 'Power SEO',
        'description' => 'Provide Power SEO to October CMS Pages, Static Pages, Blog post',
    ],
    'editor' => [
        'meta_title' => 'Meta Title for Power SEO',
        'meta_description' => 'Meta Description for Power SEO',
        'meta_keywords' => 'Meta Keywords',
        'canonical_url' => 'Canonical URL',
        'redirect_url' => 'Redirect URL',
        'robot_index' => 'Robot Index',
        'robot_follow' => 'Robot Follow',
    ],
    'settings' => [
        'label' => 'Power SEO',
        'description' => 'Configure Power SEO',
        'permissions' => [
            'settings_edit' => 'Configure Power SEO'
        ],
        'tab_settings' => [
            'label' => 'Settings',
            'site' => 'Use site name in title',
            'site_comment' => 'Enable if you want to use site name in title tag',
            'sitename' => 'Site name',
            'canonical' => 'Use default URL as canonical URL',
            'canonical_comment' => 'if canonical URL is not provided then use default URL as canonical URL',
            'sitename_comment_above' => 'Prefix or suffix site name in title tag',
            'sitename_comment' => 'Site name | <seo/page/blog title>',
            'sitename_placeholder' => 'Sitename |',
            'title_position' => 'Site name appear at',
            'title_position_comment' => 'select where site name should appear i.e. at start or at end',
            'title_position_prefix' => 'Prefix (at start)',
            'title_position_suffix' => 'Suffix (at end)',
            'other_tags' => 'Other meta tags',
            'other_tags_comment_above' => 'Insert tags that you want to insert in all pages',
            'other_tags_comment' => 'Insert other meta tags like meta author, meta viewport etc',
        ],
        'tab_og' => [
            'label' => 'Open Graph',
            'og' => 'Use Open Graph(og)',
            'og_comment' => 'Enable Open Graph(og) Tags',
            'sitename' => 'Site name for Open Graph',
            'sitename_comment' => 'The name of your website. Not the URL, but the name. (i.e. "Power SEO" not "powerseo.com".)',
            'fb' => 'Facebook App Id',
            'fb_comment' => 'The unique ID that lets Facebook know the identity of your site.',
        ],
    ],
    'component' => [
        'blog' => [
            'name' => 'SEO Blog Post',
            'description' => 'Inject SEO Fields of blog post',
        ],
        'cms' => [
            'name' => 'SEO CMS Page',
            'description' => 'Inject SEO Fields of CMS pages',
        ],
        'static' => [
            'name' => 'SEO Static Page',
            'description' => 'Inject SEO fields of Static Pages',
        ],
    ],
];