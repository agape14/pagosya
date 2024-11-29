<?php

namespace App\Http\Controllers;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index_video()
    {
        $page_title = 'Panel de Control';
        $page_description = 'Some description for the page';
        $action = __FUNCTION__;
        $videos = Video::all();
        //dd( $videos);
        return view('videos.index', compact('videos','page_title','page_description','action'));
    }
}
