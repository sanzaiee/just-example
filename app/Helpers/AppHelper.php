<?php

namespace App\Helpers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Faq;
use App\Models\OurTeam;
use App\Models\Testimonial;

class AppHelper
{
    private $category;
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function categories($limit)
    {
        if ($limit) {
            return  Category::where('enabled', 1)->limit($limit)->get();
        }
        return  Category::where('enabled', 1)->get();
    }

    public function testimonials($limit)
    {
        if ($limit) {
            return  Testimonial::where('enabled', 1)->limit($limit)->get();
        }
        return  Testimonial::where('enabled', 1)->get();
    }

    public function blog($limit)
    {
        if ($limit) {
            return  Blog::where('enabled', 1)->limit($limit)->get();
        }
        return  Blog::where('enabled', 1)->get();
    }

    public function latestBlog($limit)
    {
        if ($limit) {
            return  Blog::where('enabled', 1)->orderBy('created_at', 'desc')->limit($limit)->get();
        }
        return  Blog::where('enabled', 1)->orderBy('created_at', 'desc')->get();
    }

    public function ourTeam($limit)
    {
        if ($limit) {
            return  OurTeam::where('enabled', 1)->limit($limit)->get();
        }
        return OurTeam::where('enabled', 1)->get();
    }


    public function faq($limit)
    {
        if ($limit) {
            return  Faq::where('enabled', 1)->limit($limit)->get();
        }
        return Faq::where('enabled', 1)->get();
    }

}
