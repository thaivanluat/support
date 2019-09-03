<?php namespace RainLab\Blog\Components;

use Db;
use Carbon\Carbon;
use Cms\Classes\Page;
use RainLab\Blog\Classes\ComponentAbstract;
use RainLab\Blog\Models\Category as BlogCategory;
use RainLab\Blog\Models\Post as BlogPost;

class Categories extends ComponentAbstract
{
    /**
     * @var Collection A collection of categories to display
     */
    public $categories;

    /**
     * @var string Reference to the page name for linking to categories.
     */
    public $categoryPage;

    /**
     * @var string Reference to the current category slug.
     */
    public $currentCategorySlug;

    public function componentDetails()
    {
        return [
            'name'        => 'rainlab.blog::lang.settings.category_title',
            'description' => 'rainlab.blog::lang.settings.category_description'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'rainlab.blog::lang.settings.category_slug',
                'description' => 'rainlab.blog::lang.settings.category_slug_description',
                'default'     => '{{ :slug }}',
                'type'        => 'string',
            ],
            'displayEmpty' => [
                'title'       => 'rainlab.blog::lang.settings.category_display_empty',
                'description' => 'rainlab.blog::lang.settings.category_display_empty_description',
                'type'        => 'checkbox',
                'default'     => 0,
            ],
            'categoryPage' => [
                'title'       => 'rainlab.blog::lang.settings.category_page',
                'description' => 'rainlab.blog::lang.settings.category_page_description',
                'type'        => 'dropdown',
                'default'     => 'blog/category',
                'group'       => 'rainlab.blog::lang.settings.group_links',
            ],

        ];
    }

    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->currentCategorySlug = $this->property('slug');
        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage');
        $this->categories = $this->page['categories'] = $this->loadCategories();
        dump($this->categories);
    }

    /**
     * Load all categories or, depending on the <displayEmpty> option, only those that have blog posts
     * @return mixed
     */
    protected function loadCategories()
    {
        //$categories = BlogCategory::with('posts_count')->getNested();
        //TruongBui
        //Get categories by parent_id
        $parentId = (int)$this->loadCategory($this->currentCategorySlug)->first()->attributes['id'];
        $categories = BlogCategory::where('parent_id',$parentId)->get();
        if (!$this->property('displayEmpty')) {
            $iterator = function ($categories) use (&$iterator) {
                return $categories->reject(function ($category) use (&$iterator) {
                    if ($category->getNestedPostCount() == 0) {
                        return true;
                    }
                    if ($category->children) {
                        $category->children = $iterator($category->children);
                    }
                    return false;
                });
            };
            $categories = $iterator($categories);
        }

        /*
         * Add a "url" helper attribute for linking to each category
         */
        return $this->linkCategories($categories);
    }

    protected function loadCategory($slug)
    {
        $category = new BlogCategory;

        $category = $category->isClassExtendedWith('RainLab.Translate.Behaviors.TranslatableModel')
            ? $category->transWhere('slug', $slug)
            : $category->where('slug', $slug);

        return $category ?: null;
    }

    protected function linkCategories($categories)
    {
        $blogPostsComponent = $this->getComponent('blogPosts', $this->categoryPage);

        return $categories->each(function ($category) use ($blogPostsComponent) {
            $category->setUrl(
                $this->categoryPage,
                $this->controller,
                [
                    'slug' => $this->urlProperty($blogPostsComponent, 'categoryFilter')
                ]
            );

            if ($category->children) {
                $this->linkCategories($category->children);
            }
        });
    }
}
