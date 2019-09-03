<?php namespace RainLab\Blog\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRainlabBlogPostsCategories3 extends Migration
{
    public function up()
    {
        Schema::table('rainlab_blog_posts_categories', function($table)
        {
            $table->dropColumn('munu');
        });
    }
    
    public function down()
    {
        Schema::table('rainlab_blog_posts_categories', function($table)
        {
            $table->integer('munu');
        });
    }
}
