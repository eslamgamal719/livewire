<?php

namespace App\Http\Livewire;

use App\Post;
use App\Category;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;


class EditPost extends Component
{

    use WithFileUploads;

    public $post;
    public $post_id;
    public $title;
    public $category;
    public $body;
    public $image;
    public $image_original;

    public function mount() 
    {
        $this->post_id = request()->post_id;
        $this->post = Post::whereId($this->post_id)->whereUserId(auth()->id())->first();
        $this->title = $this->post->title;
        $this->body = $this->post->body;
        $this->category = $this->post->category_id;
        $this->image_original = $this->post->image;
        $this->image = $this->post->image;
    }


    public function render()
    {
        $categories = Category::all();
        return view('livewire.edit-post', [
            'categories' => $categories,
            'post' => $this->post
        ]);
    }


    public function update()
    {
        $this->validate([
            'title' => 'required|max:255',
            'category' => 'required',
            'body' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif|max:20000'
        ]);

        $post = Post::whereId($this->post_id)->whereUserId(auth()->id())->first();
        if($post) {
            $data['title']        = $this->title;
            $data['body']         = $this->body;
            $data['category_id']  = $this->category;
    
            if($image = $this->image) {
                if(File::exists("assets/images/" . $this->image_original)) {
                    unlink("assets/images/" . $this->image_original);
                }
                $fileName = Str::slug($this->title) . '.' . $image->getClientOriginalExtension();
                $path = public_path('/assets/images/' . $fileName);
    
                Image::make($image->getRealPath())->save($path, 100);
    
                $data['image'] = $fileName;
            }
    
            $post->update($data);
    
            $this->resetInputs();
    
            session()->flash('message', 'Post Updated Successfully');
            return redirect()->to('livewire/posts');
        }

        session()->flash('message_error', 'You Can not update not yours!');
        return redirect()->to('livewire/posts');
    }


    private function resetInputs() 
    {
        $this->title    = null;
        $this->category = null;
        $this->body     = null;
        $this->image    = null;
    }


    public function return_to_posts()
    {
        return redirect()->to('/livewire/posts');
    }


}
