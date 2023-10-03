<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
 * https://coding-lesson.com/creating-dynamic-nested-menus-in-laravel-with-relationships/
 */
class MenuItem extends Model
{
    use HasFactory;
    
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}