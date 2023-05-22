<?php

namespace App\Http\Controllers;

use App\Models\HomepageBannerPicture;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class UploadBannerController extends Controller
{
    public function index()
    {
        return view('uploadBanner');
    }

    public function uploadBanner(Request $request)
    {
        $image = $request->file('image');
        $name = Str::uuid();
        $fileextension = 'jpeg';
        $filename = $name . '.' . $fileextension;
        $image->move(public_path('images/homepage_banners/'), $filename);

        $homepage_banner = new HomepageBannerPicture();
        $homepage_banner->banner_id = $filename;
        $homepage_banner->save();
        return redirect()->to('/');
    }
}
