<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Candidate;
use App\Models\Category;
use App\Models\FAQ;
use App\Models\Member;
use App\Models\OurTeam;
use App\Models\Testimonial;
use App\Models\Winner;
use Illuminate\Http\Request;

class RecycleBinController extends Controller
{
    public function index()
    {
        $blogs = Blog::onlyTrashed()->count();
        $teams = OurTeam::onlyTrashed()->count();
        $testimonials = Testimonial::onlyTrashed()->count();
        $faqs = FAQ::onlyTrashed()->count();
        $banners = Banner::onlyTrashed()->count();

        return view('backend.bin.index', compact('blogs', 'teams', 'testimonials', 'faqs','banners'));
    }


    public function show($parameter){
        switch ($parameter) {
            case 'blog':
                $data = Blog::onlyTrashed()->paginate(15);
            break;

            case 'team':
                $data = OurTeam::onlyTrashed()->paginate(15);
            break;

            case 'testimonial':
                $data = Testimonial::onlyTrashed()->paginate(15);
            break;

            case 'faq':
                $data = FAQ::onlyTrashed()->paginate(15);
            break;


            case 'banner':
                $data = Banner::onlyTrashed()->paginate(15);
            break;

            default:
                return back();
            break;
        }

        return view('backend.bin.recycle-bin',compact('data','parameter'));
    }

    public function restore($id,$parameter){
        switch ($parameter) {
            case 'blog':
               Blog::onlyTrashed()->findOrFail($id)->restore();
            break;

            case 'team':
               OurTeam::onlyTrashed()->findOrFail($id)->restore();
            break;

            case 'testimonial':
               Testimonial::onlyTrashed()->findOrFail($id)->restore();
            break;

            case 'faq':
               FAQ::onlyTrashed()->findOrFail($id)->restore();
            break;

            case 'banner':
                Banner::onlyTrashed()->findOrFail($id)->restore();
             break;

            default:
                return back();
            break;
        }

        return back()->with('success','Restored Successfully!');
    }
}
