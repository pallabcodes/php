<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    function index()
    {
        // Query Builder
        // DB::table('users')->insert(
        //     [
        //         [
        //             'name' => "John",
        //             'email' => "john12@gmail.com",
        //             'password' => "123456"
        //         ],
        //         [
        //             'name' => "James",
        //             'email' => "james12@gmail.com",
        //             'password' => "123456"
        //         ]
        //     ]
        // );


        // $users = DB::table("users")->get()->where('id', 1)->first();
        // $users = DB::table("users")->get()->where('email', 'john12@gmail.com')->where('id', 8)->first();
        // return $users;

        // DB::table('users')->where('id', 1)->update(['email' => 'john10@gmail.com']);

        // DB::table("users")->where('id', 1)->delete();

        // $showBlogs = DB::table('blogs')->select('title')->get();
        // $showBlogs = DB::table('blogs')->pluck('title', 'id')->toArray();
        // dd($showBlogs);

        $products = DB::table('products')->min('price');
        // $products = DB::table('products')->sum('price');

        return view('welcome');
    }

    function showAboutPage()
    {
        return view("about.index");
    }
}

// from 45

// https://tutflix.org/resources/the-no-bs-solution-for-enterprise-ready-next-js-applications-jack-herrington.10977/
// https://tutflix.org/resources/amigoscode-java-generics.8539/
// https://tutflix.org/threads/amigoscode-java-streams-api.38013/
// https://tutflix.org/threads/amigoscode-database-design-implementation.29301/
// https://pastebin.com/iE0HqcwL
// https://rentry.co/7pG6K7uTpVKa6NVq