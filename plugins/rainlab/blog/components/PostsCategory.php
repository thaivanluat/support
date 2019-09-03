<?php namespace RainLab\Blog\Components;

use Db;
use Lang;
use Redirect;
use BackendAuth;
use Cms\Classes\Page;
use October\Rain\Database\Model;
use October\Rain\Database\Collection;
use RainLab\Blog\Models\Post as BlogPost;
use RainLab\Blog\Classes\ComponentAbstract;
use RainLab\Blog\Models\Category as BlogCategory;
use RainLab\Blog\Models\Settings as BlogSettings;

class PostsCategory extends ComponentAbstract
{

    /**
     * Reference to the page name for linking to posts
     *
     * @var string
     */
    public $postPage;

    /**
     * Reference to the page name for linking to categories
     *
     * @var string
     */
    public $categoryPage;

    /**
     * Reference to the list post
     *
     * @var collection
     */
    public $posts;

    /**
     * Truong Bui
     * Reference to the list category filter by parent category
     *
     * @var collection
     */
    public $categories;

    /**
     * Truong Bui
     * Reference to the list post filter by category
     *
     * @var collection
     */
    public $postsCategory;

    public function componentDetails()
    {
        return [
            'name'        => 'rainlab.blog::lang.settings.posts_filter_by_category',
            'description' => 'rainlab.blog::lang.settings.posts_filter_by_category_description'
        ];
    }

    public function defineProperties()
    {
        return [
            'postsPerColumn' => [
                'title'             => 'rainlab.blog::lang.settings.posts_per_page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'rainlab.blog::lang.settings.posts_per_page_validation',
                'default'           => '10',
            ],
            'sortOrder' => [
                'title'       => 'rainlab.blog::lang.settings.posts_order',
                'description' => 'rainlab.blog::lang.settings.posts_order_description',
                'type'        => 'dropdown',
                'default'     => 'published_at desc',
            ],
            'parent' => [
                'title'       => 'rainlab.blog::lang.settings.category_parent',
                'description' => 'rainlab.blog::lang.settings.category_parent_description',
                'default'     => 'tai-lieu-huong-dan',
                'type'        => 'string',
            ],
            'categoryPage' => [
                'title'       => 'rainlab.blog::lang.settings.posts_category',
                'description' => 'rainlab.blog::lang.settings.posts_category_description',
                'type'        => 'dropdown',
                'default'     => 'blog/category',
                'group'       => 'rainlab.blog::lang.settings.group_links',
            ],
            'postPage' => [
                'title'       => 'rainlab.blog::lang.settings.posts_post',
                'description' => 'rainlab.blog::lang.settings.posts_post_description',
                'type'        => 'dropdown',
                'default'     => 'blog/post',
                'group'       => 'rainlab.blog::lang.settings.group_links',
            ],
            'postsPerColumn' => [
                'title'             => 'rainlab.blog::lang.settings.posts_per_page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'rainlab.blog::lang.settings.posts_per_page_validation',
                'default'           => '10',
            ],
            'sortOrder' => [
                'title'       => 'rainlab.blog::lang.settings.posts_order',
                'description' => 'rainlab.blog::lang.settings.posts_order_description',
                'type'        => 'dropdown',
                'default'     => 'published_at desc',
            ],
            'exceptPost' => [
                'title'             => 'rainlab.blog::lang.settings.posts_except_post',
                'description'       => 'rainlab.blog::lang.settings.posts_except_post_description',
                'type'              => 'string',
                'validationPattern' => '^[a-z0-9\-_,\s]+$',
                'validationMessage' => 'rainlab.blog::lang.settings.posts_except_post_validation',
                'default'           => '',
                'group'             => 'rainlab.blog::lang.settings.group_exceptions',
            ],
            'exceptCategories' => [
                'title'             => 'rainlab.blog::lang.settings.posts_except_categories',
                'description'       => 'rainlab.blog::lang.settings.posts_except_categories_description',
                'type'              => 'string',
                'validationPattern' => '^[a-z0-9\-_,\s]+$',
                'validationMessage' => 'rainlab.blog::lang.settings.posts_except_categories_validation',
                'default'           => '',
                'group'             => 'rainlab.blog::lang.settings.group_exceptions',
            ]
        ];
    }

    public function getSortOrderOptions()
    {
        $options = BlogPost::$allowedSortingOptions;

        foreach ($options as $key => $value) {
            $options[$key] = Lang::get($value);
        }

        return $options;
    }

    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getPostPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    protected function prepareVars()
    {
        /*
         * Page links
         */
        $this->postPage = $this->page['postPage'] = $this->property('postPage');
        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage');
    }


    public function onRun()
    {
        $this->prepareVars();
        $parent_id = (int)BlogCategory::where('slug', $this->property('parent'))->remember(30)->first()->attributes['id'];

        // Get category child filter by category parent
        $this->categories = BlogCategory::where('parent_id', $parent_id)->orderBy('ordinal','ASC')->remember(30)->get();

        // Get list post filter by each category child
        $i = 0;
        foreach( $this->categories as $category) {
            $this->posts = $this->page['posts'] = $this->listPosts($category->attributes['id']);
            foreach( $this->posts as $post) {
                $this->postsCategory[$i][] = $post;
            }
            $i++;
        }
    }

    protected function listPosts($category)
    {

        /*
         * List all the posts, eager load their categories
         */

        $posts = Db::table('rainlab_blog_posts')
                ->join('rainlab_blog_posts_categories', 'rainlab_blog_posts.id', '=', 'rainlab_blog_posts_categories.post_id')
                ->select('rainlab_blog_posts.*')->where(['rainlab_blog_posts_categories.category_id' => $category],
                    ['rainlab_blog_posts.published' => 1])
                ->remember(30)->get();

        /*
         * Add a "url" helper attribute for linking to each post and category
         */
        
        return $posts;
    }

    protected function checkEditor()
    {
        $backendUser = BackendAuth::getUser();

        return $backendUser && $backendUser->hasAccess('rainlab.blog.access_posts') && BlogSettings::get('show_all_posts', true);
    }


}
