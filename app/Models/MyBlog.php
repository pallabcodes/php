<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyBlog extends Model
{
    use HasFactory;

    protected $table = "blogs"; // so, whereever MyBlog used it will refere to blogs table i.e. creted with same name through migration
}
