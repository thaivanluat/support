<?php namespace RainLab\Blog\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRainlabBlogPostsCategories extends Migration
{
    public function up()
    {
        Schema::table('rainlab_blog_posts_categories', function($table)
        {
            $table->integer('ds');
        });
    }
    
    public function down()
    {
        Schema::table('rainlab_blog_posts_categories', function($table)
        {
            $table->dropColumn('ds');
        });
    }
}
