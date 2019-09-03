<?php namespace RainLab\Blog\Components;

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
use Cms\Classes\ComponentBase;
use Input;
use Db;

class Posts extends ComponentAbstract
{

    /**
     * A collection of posts to display
     *
     * @var Collection
     */
    public $posts;

    /**
     * Parameter to use for the page number
     *
     * @var string
     */
    public $pageParam;

    /**
     * If the post list should be filtered by a category, the model to use
     *
     * @var Model
     */
    public $category;

    /**
     * Message to display when there are no messages
     *
     * @var string
     */
    public $noPostsMessage;

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
     * If the post list should be ordered by another attribute
     *
     * @var string
     */
    public $sortOrder;

    /**
     * Truong Bui
     * Reference to the first post filter by category
     *
     * @var blogPost
     */
    public $postDefault;

    /**
     * Truong Bui
     * Reference to the list post filter by category
     *
     * @var collection blogPosts
     */
    public $postListCategory;


    public function componentDetails()
    {
        return [
            'name'        => 'rainlab.blog::lang.settings.posts_title',
            'description' => 'rainlab.blog::lang.settings.posts_description'
        ];
    }

    public function defineProperties()
    {
        return [
            'pageNumber' => [
                'title'       => 'rainlab.blog::lang.settings.posts_pagination',
                'description' => 'rainlab.blog::lang.settings.posts_pagination_description',
                'type'        => 'string',
                'default'     => '{{ :page }}',
            ],
            'categoryFilter' => [
                'title'       => 'rainlab.blog::lang.settings.posts_filter',
                'description' => 'rainlab.blog::lang.settings.posts_filter_description',
                'type'        => 'string',
                'default'     => '',
            ],
            'locationFilter' => [
                'title'       => 'rainlab.blog::lang.settings.location_filter',
                'description' => 'rainlab.blog::lang.settings.location_filter_description',
                'type'        => 'string',
                'default'     => '',
            ],
            'postsPerPage' => [
                'title'             => 'rainlab.blog::lang.settings.posts_per_page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'rainlab.blog::lang.settings.posts_per_page_validation',
                'default'           => '10',
            ],
            'noPostsMessage' => [
                'title'             => 'rainlab.blog::lang.settings.posts_no_posts',
                'description'       => 'rainlab.blog::lang.settings.posts_no_posts_description',
                'type'              => 'string',
                'default'           => Lang::get('rainlab.blog::lang.settings.posts_no_posts_default'),
                'showExternalParam' => false,
            ],
            'sortOrder' => [
                'title'       => 'rainlab.blog::lang.settings.posts_order',
                'description' => 'rainlab.blog::lang.settings.posts_order_description',
                'type'        => 'dropdown',
                'default'     => 'published_at desc',
            ],
            // 'categoryPage' => [
            //     'title'       => 'rainlab.blog::lang.settings.posts_category',
            //     'description' => 'rainlab.blog::lang.settings.posts_category_description',
            //     'type'        => 'dropdown',
            //     'default'     => 'blog/category',
            //     'group'       => 'rainlab.blog::lang.settings.group_links',
            // ],
            // 'postPage' => [
            //     'title'       => 'rainlab.blog::lang.settings.posts_post',
            //     'description' => 'rainlab.blog::lang.settings.posts_post_description',
            //     'type'        => 'dropdown',
            //     'default'     => 'blog/post',
            //     'group'       => 'rainlab.blog::lang.settings.group_links',
            // ],
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
            ],

        ];
    }

    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getPostPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getSortOrderOptions()
    {
        $options = BlogPost::$allowedSortingOptions;

        foreach ($options as $key => $value) {
            $options[$key] = Lang::get($value);
        }

        return $options;
    }

    public function onRun()
    {
        $this->prepareVars();

        $categorySlug = $this->property('categoryFilter');
        $location = $this->property('locationFilter');


        $this->category = $this->page['category'] = $this->loadCategory($categorySlug);
        $this->posts = $this->page['posts'] = $this->listPosts();

        /*Truong Bui
        *Get the first post filter by category to show default
        */
        if($this->category){
            $postDefault = BlogPost::with('categories')->listFrontEnd([
                'category'         => $this->category->attributes['id'],
            ]);
            $this->postDefault = $postDefault->first();
        }


        /*
         * If the page number is not valid, redirect
         */
        if ($pageNumberParam = $this->paramName('pageNumber')) {
            $currentPage = $this->property('pageNumber');

            if ($currentPage > ($lastPage = $this->posts->lastPage()) && $currentPage > 1) {
                return Redirect::to($this->currentPageUrl([$pageNumberParam => $lastPage]));
            }
        }
    }

    protected function prepareVars()
    {
        $this->pageParam = $this->page['pageParam'] = $this->paramName('pageNumber');
        $this->noPostsMessage = $this->page['noPostsMessage'] = $this->property('noPostsMessage');

        /*
         * Page links
         */
        $this->postPage = $this->page['postPage'] = $this->property('postPage');
        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage');
    }

    protected function listPosts()
    {
        $category = $this->category ? $this->category->id : null;

        /*
         * List all the posts, eager load their categories
         */
        $isPublished = !$this->checkEditor();
        if($this->property('locationFilter'))
        {
            $posts = BlogPost::where('location',$this->property('locationFilter'))->with('categories')->listFrontEnd([
                'page'             => $this->property('pageNumber'),
                'sort'             => $this->property('sortOrder'),
                'perPage'          => $this->property('postsPerPage'),
                'search'           => trim(input('search')),
                'category'         => $category,
                'published'        => $isPublished,
                'exceptPost'       => is_array($this->property('exceptPost'))
                    ? $this->property('exceptPost')
                    : preg_split('/,\s*/', $this->property('exceptPost'), -1, PREG_SPLIT_NO_EMPTY),
                'exceptCategories' => is_array($this->property('exceptCategories'))
                    ? $this->property('exceptCategories')
                    : preg_split('/,\s*/', $this->property('exceptCategories'), -1, PREG_SPLIT_NO_EMPTY),
            ]);
        }
        else{
            $posts = BlogPost::with('categories')->listFrontEnd([
                'page'             => $this->property('pageNumber'),
                'sort'             => $this->property('sortOrder'),
                'perPage'          => $this->property('postsPerPage'),
                'search'           => trim(input('search')),
                'category'         => $category,
                'published'        => $isPublished,
                'exceptPost'       => is_array($this->property('exceptPost'))
                    ? $this->property('exceptPost')
                    : preg_split('/,\s*/', $this->property('exceptPost'), -1, PREG_SPLIT_NO_EMPTY),
                'exceptCategories' => is_array($this->property('exceptCategories'))
                    ? $this->property('exceptCategories')
                    : preg_split('/,\s*/', $this->property('exceptCategories'), -1, PREG_SPLIT_NO_EMPTY),
            ]);
        }
        /*
         * Add a "url" helper attribute for linking to each post and category
         */
        // $blogPostComponent = $this->getComponent('blogPost', $this->postPage);
        // $blogPostsComponent = $this->getComponent('blogPosts', $this->categoryPage);

        // $posts->each(function ($post) use ($blogPostComponent, $blogPostsComponent) {
        //     $post->setUrl(
        //         $this->postPage,
        //         $this->controller,
        //         [
        //             'slug' => $this->urlProperty($blogPostComponent, 'slug')
        //         ]
        //     );

        //     $post->categories->each(function ($category) use ($blogPostsComponent) {
        //         $category->setUrl(
        //             $this->categoryPage,
        //             $this->controller,
        //             [
        //                 'slug' => $this->urlProperty($blogPostsComponent, 'categoryFilter')
        //             ]
        //         );
        //     });
        // });

        //Truong Bui 6/8
        foreach($posts as $post){
            $postSlug = $post->slug;
            $currentCategory = $this->loadCategoryFromSlug($postSlug);
            $controller = $this->controller;
            $parent_id = null;
            $categoryPath = [];
            if ( $currentCategory ) {
                $parent_id = $currentCategory->parent_id;
                $categoryPath []= $currentCategory;
            }
            $this->getLastestParent($parent_id, $categoryPath);
            $categoryPath = array_reverse($categoryPath);
            $linkPost = [];
            // echo "<pre>"; var_dump($categoryPath);echo "</pre>";
            if ( $categoryPath ) {
                //Set link for the post
                $categoryMasterSlug = $categoryPath[0]->slug;
                //if the post in category "Q&A", the linkPost will get with file faqsPost
                //else the linkPost will get with file post
                $category2 = count($categoryPath)>2 ? $categoryPath[2]->slug : "default";
                $params = [
                    'category1' => $categoryPath[1]->slug,
                    'category2' => $category2,
                    'slug'      => $postSlug
                ];
                $linkPost = [
                    'link' => $controller->pageUrl('post', $params),
                    'name' => $post->title,
                    'separator' => null
                ];

                $post->attributes['url'] = $this->page['linkPost'] = $linkPost['link'];
            }
        }
        //!Truong Bui 6/8
        return $posts;
    }


    protected function loadCategory($slug)
    {
        $category = new BlogCategory;

        $category = $category->isClassExtendedWith('RainLab.Translate.Behaviors.TranslatableModel')
            ? $category->transWhere('slug', $slug)
            : $category->where('slug', $slug);

        $category = $category->first();

        return $category ?: null;
    }

    protected function checkEditor()
    {
        $backendUser = BackendAuth::getUser();

        return $backendUser && $backendUser->hasAccess('rainlab.blog.access_posts') && BlogSettings::get('show_all_posts', true);
    }

    //Truong Bui - 6/8/2019

    protected function loadCategoryFromSlug($postSlug)
    {
            $firstCategory = Db::table('rainlab_blog_posts')
            ->join('rainlab_blog_posts_categories', 'rainlab_blog_posts_categories.post_id', '=', 'rainlab_blog_posts.id')
            ->join('rainlab_blog_categories', 'rainlab_blog_categories.id', '=', 'rainlab_blog_posts_categories.category_id')
            ->select('rainlab_blog_categories.id')
            ->where('rainlab_blog_posts.slug', $postSlug)
            ->first();

            $categoryId = 0;
            if ( $firstCategory ) {
                $categoryId = $firstCategory->id;
            }

            if (!$category = BlogCategory::find($categoryId)) return null;
            //echo "<pre>"; var_dump($category->name); die;


        return $category;
    }

    public function getLastestParent($parent_id, &$categoryPath = [])
    {
        if ( !$parent_id ) {
            return null;
        }
        //echo $parent_id,"<hr/>";
        $category = BlogCategory::where('id', intval($parent_id))->first();
        if ( is_object($category) && $category->id ) {
            $categoryPath []= $category;
            if ( intval($category->parent_id) ) $this->getLastestParent($category->parent_id, $categoryPath);
            return $categoryPath;
        }
        return null;
    }
    //!Truong Bui - 6/8/2019
}

