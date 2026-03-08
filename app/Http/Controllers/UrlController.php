<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Models\Url;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

class UrlController extends Controller
{
    public function shorten(UrlRequest $request){
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'original_url' => ['required', 'url', 'max:255'],
        ]);
        Url::create([
            'name' => $validated['name'],
            'original_url' => $validated['original_url'],
            'short_url' => config('app.url') . '/' . Str::random(6),
            'user_id' => Auth::id(),
        ]);

        session()->flash('success', 'Successfully Shortened');
        return redirect()->route('home');
    }

    public function destroy(Url $url) {
        $url->delete();
        session()->flash('success', 'URL deleted');
        return redirect()->route('home');
    }

    public function redirect($shortUrl)
    {
        $originalUrl = Redis::get("url:{$shortUrl}");

        if (!$originalUrl) {
            $url = Url::where('short_url', config('app.url') . '/' . $shortUrl)->first();

            if (!$url) {
                abort(404);
            }

            $originalUrl = $url->original_url;
            Redis::setex("url:{$shortUrl}", 3600, $originalUrl);
        }

        return redirect($originalUrl);
    }
}