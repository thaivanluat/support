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

class CategoryThreeLevel extends ComponentAbstract
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
     * Author: Truong Bui
     * Reference the list post filter by category
     *
     * @var collection
     */
    public $postListCategory;

    /**
     * Author: Truong Bui
     * Reference to the parent category name from URL
     *
     * @var string
     */
    public $currentCategorySlug1;

    /**
     * Author: Truong Bui
     * Reference to the child category name from URL
     *
     * @var string
     */
    public $currentCategorySlug2;

    /**
     * Author: Truong Bui
     * Reference to the current post slug
     *
     * @var string
     */
    public $currentPostSlug;

    /**
     * Author: Truong Bui
     * Reference to the page name for linking to categories
     *
     * @var model blogCategory
     */
    public $categoryParent;

    /**
     * Author: Truong Bui
     * Reference to the list category parent for level 1 in sidebar
     *
     * @var collection
     */
    public $categoriesParent;

    /**
     * Author: Truong Bui
     * Reference to the list child for level 2 in sidebar
     *
     * @var collection
     */
    public $categoriesChild;

    /**
     * Author: Truong Bui
     * Reference to the grand-parent category for FAQS
     *
     * @var model
     */
    public $categoryMaster;

    /**
     * Author: Truong Bui
     * Reference to the post default for each category
     *
     * @var model blogPost
     */
    public $postDefault;

    public function componentDetails()
    {
        return [
            'name'        => 'rainlab.blog::lang.settings.CategoryThreeLevel',
            'description' => 'rainlab.blog::lang.settings.CategoryThreeLevel_description'
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
            'postSlug' => [
                'title'       => 'rainlab.blog::lang.settings.post_slug',
                'description' => 'rainlab.blog::lang.settings.post_slug_description',
                'type'        => 'string',
                'default'     => 'default',
            ],
            'categoryMaster' => [
                'title'       => 'rainlab.blog::lang.settings.category_master',
                'description' => 'rainlab.blog::lang.settings.category_master_description',
                'type'        => 'string',
                'default'     => 'default',
            ],
            'categoryParentFilter' => [
                'title'       => 'rainlab.blog::lang.settings.category_filter',
                'description' => 'rainlab.blog::lang.settings.category_filter_description',
                'type'        => 'string',
                'default'     => 'default',
            ],
            'categoryFilter' => [
                'title'       => 'rainlab.blog::lang.settings.posts_filter',
                'description' => 'rainlab.blog::lang.settings.posts_filter_description',
                'type'        => 'string',
                'default'     => 'default',
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
        //get current category on url
        $this->currentCategorySlug1 = $this->property('categoryParentFilter');
        $this->currentCategorySlug2 = $this->property('categoryFilter');
        $this->currentPostSlug = $this->property('postSlug');
        //get current parent category
        $this->categoryParent = $this->loadCategory($this->currentCategorySlug1)->first();
        //get children by current parent category
        $this->categoriesChild = BlogCategory::where('parent_id', $this->categoryParent->id)->remember(30)->get();
        // if isset child then get category with currentCategorySlug2
        //else get category with currentCategorySlug1
        if($this->categoriesChild->count()) {
            if( !$this->currentCategorySlug2 ) {
                $categoryChildSlugDefault = $this->categoriesChild->first()->slug;
                $this->category = $this->loadCategory($categoryChildSlugDefault)->first();
            }
            else {
                $this->category = $this->loadCategory($this->currentCategorySlug2)->first();
            }
        }
        else {
            $this->category = $this->loadCategory($this->currentCategorySlug1)->first();
        }

        $this->categoryMaster = $this->loadCategory($this->property('categoryMaster'))->first();
        $this->categoriesParent = BlogCategory::where('parent_id',$this->categoryMaster->id)->remember(30)->get();

        //Get category child slug default and post slug default to show on sidebar
        $this->setCategoryChildSlugDefault();
        $this->setPostSlugDefault();

        $this->posts = $this->page['posts'] = $this->listPosts($this->category->id);

        $this->postDefault = $this->posts->first();

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

    public function setCategoryChildSlugDefault() {
        foreach($this->categoriesParent as $categoryParent){
            $categoriesChild = BlogCategory::where('parent_id', $categoryParent->id)->remember(30)->get();
            if($categoriesChild->count() != 0){
                $categoryChildDefault = $categoriesChild->first() ;
                $categoryParent->attributes['slug2'] = $categoryChildDefault->slug;
                    $postDefault = $this->listPosts($categoryChildDefault->id)->first();
                    $categoryParent->attributes['slugPostDefault'] = $postDefault? $postDefault->slug:'';
            }
            else {
                    $postDefault = BlogPost::with('categories')->remember(30)->listFrontEnd([
                        'category'         => $categoryParent->id,
                    ])->first();
                    $categoryParent->attributes['slugPostDefault'] = $postDefault? $postDefault->slug:'';
            }
        }
    }

    public function setPostSlugDefault() {
        foreach ($this->categoriesChild as $categoryChild) {
            $postDefault = $this->listPosts($categoryChild->id)->first();
            $categoryChild->attributes['slugPostDefault'] = $postDefault? $postDefault->slug:'';

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

    protected function listPosts($categoryId)
    {
        /*
         * List all the posts, eager load their categories
         */
        $isPublished = !$this->checkEditor();

        $posts = BlogPost::with('categories')->remember(30)->listFrontEnd([
            'page'             => $this->property('pageNumber'),
            'sort'             => $this->property('sortOrder'),
            'perPage'          => $this->property('postsPerPage'),
            'search'           => trim(input('search')),
            'category'         => $categoryId,
            'published'        => $isPublished,
            'exceptPost'       => is_array($this->property('exceptPost'))
                ? $this->property('exceptPost')
                : preg_split('/,\s*/', $this->property('exceptPost'), -1, PREG_SPLIT_NO_EMPTY),
            'exceptCategories' => is_array($this->property('exceptCategories'))
                ? $this->property('exceptCategories')
                : preg_split('/,\s*/', $this->property('exceptCategories'), -1, PREG_SPLIT_NO_EMPTY),
        ]);

        /*
         * Add a "url" helper attribute for linking to each post and category
         */
        $blogPostComponent = $this->getComponent('blogPost', $this->postPage);
        $blogPostsComponent = $this->getComponent('blogPosts', $this->categoryPage);

        $posts->each(function ($post) use ($blogPostComponent, $blogPostsComponent) {
            $post->setUrl(
                $this->postPage,
                $this->controller,
                [
                    'slug' => $this->urlProperty($blogPostComponent, 'slug')
                ]
            );

            $post->categories->each(function ($category) use ($blogPostsComponent) {
                $category->setUrl(
                    $this->categoryPage,
                    $this->controller,
                    [
                        'slug' => $this->urlProperty($blogPostsComponent, 'categoryFilter')
                    ]
                );
            });
        });

        return $posts;
    }

    protected function loadCategory($slug)
    {
        $category = new BlogCategory;

        $category = $category->isClassExtendedWith('RainLab.Translate.Behaviors.TranslatableModel')
            ? $category->transWhere('slug', $slug)
            : $category->where('slug', $slug);

        return $category ?: null;
    }

    protected function checkEditor()
    {
        $backendUser = BackendAuth::getUser();

        return $backendUser && $backendUser->hasAccess('rainlab.blog.access_posts') && BlogSettings::get('show_all_posts', true);
    }

}
