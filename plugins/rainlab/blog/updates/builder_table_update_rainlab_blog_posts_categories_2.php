<?php namespace RainLab\Blog\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRainlabBlogPostsCategories2 extends Migration
{
    public function up()
    {
        Schema::table('rainlab_blog_posts_categories', function($table)
        {
            $table->renameColumn('ds', 'munu');
        });
    }
    
    public function down()
    {
        Schema::table('rainlab_blog_posts_categories', function($table)
        {
            $table->renameColumn('munu', 'ds');
        });
    }
}
