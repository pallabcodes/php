<?php

use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view('welcome');
});

// used the name alias so that it could be used within a blade file using "{{  route("about") }}"
Route::get("/about", function () {
    return view("about.index");
})->name("about");

Route::get("/contact", function () {
    $title = "Contact Page";
    $books = ["How to draw", "Thinking in perspective", "chracter design by tb choi", "learn to write unforgettable stories"];
    return view("contact.index", ["title" => $title, "books" => $books]);
});

Route::get("/users/{id}/{slug}", function ($id, $slug) {
    return "Hello, user " . $id . "-" . $slug;
})->name("user.detail");


// prefix will add i.e. "blogs" to all the routes within it and "as" will do the same for named alias 
Route::group(["prefix" => "blogs", "as" => "blogs."], function () {
    Route::get("/", function () {
        return "This will shows all blogs (with pagination)";
    })->name("show");

    Route::post("/create", function () {
        return "This is Blog create page";
    })->name("create");

    Route::put("/update", function () {
        return "Blog update page";
    })->name("update");
});


// Fallback Route (when hitting an endpoint that doesn't exist then it render/return this response from this route)

Route::fallback(function () {
    return "This is a fallback route";
});
