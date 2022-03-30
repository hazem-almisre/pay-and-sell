<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\Tag_Post;
use App\Models\Tags;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Profiler\Profiler;

use function PHPUnit\Framework\isNull;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $post = Profile::all();
        return view('post.index', compact('post'));
    }

    public function trash($id)
    {
        $post = Profile::onlyTrashed()->where('user_id', $id)->get();
        return view('post.trash', compact('post'));
    }

    public function create()
    {
        $tag = Tags::all();
        return view('post.create', compact('tag'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required'],
            'content' => ['required'],
            'photo' => ['required', 'image'],
            'tag' => ['required']
        ]);

        $newphoto =  $request->photo->getClientOriginalName();
        $newphoto = $request->photo->move('images', $newphoto);
        $slug = Str::slug($request->title);

        $t = Profile::create([
            'title' => $request->title,
            'content' => $request->content,
            'photo' => $newphoto,
            'slug' => $slug,
            'user_id' => Auth::id()
        ]);
        $t->tag()->attach($request->tag);
        return redirect()->route('in');
    }


    public function show($slug)
    {
        $post = Profile::query()->where('slug', $slug)->first();
        return view('post.show', compact('post'));
    }


    public function edit($id)
    {
        $tag = Tags::all();
        return view('post.update',)->with('post', Profile::query()->find($id))->with('tag', $tag);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['unique:profiles,title'],
            'content' => ['string', 'nullable'],
            'photo' => ['image', 'nullable']
        ]);

        $post = Profile::query()->find($id);
        if (!is_null($request->photo)) {
            $newphoto = time() . $_FILES['photo']['name'];
            $newphoto = $request->photo->move('image', $newphoto);
            $post->photo = $newphoto;
        }
        $post->title = (isset($request->title)) ? $request->title : $post->title;
        $post->content = (isset($request->content)) ? $request->content : $post->content;
        $post->tag()->sync($request->tag);
        $post->save();

        return redirect()->route('in');
    }

    public function destroy($id)
    {
        Profile::query()->find($id)->delete();
        return redirect()->route('in');
    }


    public function restoretrash($id)
    {
        $t = Profile::onlyTrashed()->find($id)->restore();
        return redirect()->route('tr', Auth::id());
    }


    public function deletetrash($id)
    {
        $t = Profile::onlyTrashed()->find($id);
        if (!is_null($t->photo))
            unlink($t->photo);
        $t->forceDelete();
        return redirect()->route('tr', Auth::id());
    }
}
