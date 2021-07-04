<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with(['user', 'category'])->orderBy('id', 'desc')->paginate(5);
        return view('frontend.index', compact('posts'));
        
    }


    public function index_livewire()
    {
        return view('frontend.index_livewire');
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('frontend.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request;
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'category' => 'required',
            'body' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif|max:20000'
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data['title'] = $request->title;
        $data['category_id'] = $request->category;
        $data['body'] = $request->body;
        $data['user_id'] = auth()->id();

        if($image = $request->file('image')) {
            $fileName = Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
            $path = public_path('/assets/images/' . $fileName);

            Image::make($image->getRealPath())->save($path, 100);

            $data['image'] = $fileName;
        }

        Post::create($data);

        return redirect()->route('posts.index')->with([
            'message' => 'Post Created Successfully',
            'alert-type' => 'success'
        ]);

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::with(['user', 'category'])->whereId($id)->first();
        return view('frontend.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::all();
        $post = Post::findOrFail($id);
        return view('frontend.edit', compact('categories', 'post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'category' => 'required',
            'body' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif|max:20000'
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $post = Post::findOrFail($id);

        $data['title'] = $request->title;
        $data['category_id'] = $request->category;
        $data['body'] = $request->body;

        if($image = $request->file('image')) {
            if(File::exists("assets/images/" . $post->image)) {
                unlink("assets/images/" . $post->image);
            }
            $fileName = Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
            $path = public_path('/assets/images/' . $fileName);

            Image::make($image->getRealPath())->save($path, 100);

            $data['image'] = $fileName;
        }

        $post->update($data);

        return redirect()->route('posts.index')->with([
            'message' => 'Post Updated Successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if(File::exists('assets/images/' . $post->image)) {
            unlink("assets/images/" . $post->image);
        }
        $post->delete();

        return redirect()->route('posts.index')->with([
            'message' => 'Post Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }
}
