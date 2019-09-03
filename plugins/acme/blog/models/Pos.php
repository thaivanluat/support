<?php namespace Acme\Blog\Models;

use Model;

/**
 * Pos Model
 */
class Pos extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'acme_blog_pos';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
