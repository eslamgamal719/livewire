<?php

namespace App\Http\Livewire\Dynamic;

use App\Post;
use App\Category;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;


class Edit extends Component
{

    use WithFileUploads;

    public $post_id;
    public $post;
    public $title;
    public $body;
    public $category;
    public $image;
    public $image_original;

    protected $listeners = ['getPost' => 'get_post'];


    /*public function mount() 
    {
        $this->post = Post::whereId($this->post_id)->whereUserId(auth()->id())->first();
    }*/
    

    public function render()
    {
        return view('livewire.dynamic.edit', [
            'post' => $this->post,
            'categories' => Category::all()
        ]);
    }


    public function get_post($post) 
    {
        $this->post = $post;
        $this->post_id = $this->post['id'];
        $this->title = $this->post['title'];
        $this->body = $this->post['body'];
        $this->category = $this->post['category_id'];
        $this->image = $this->post['image'];
        $this->image_original = $this->post['image'];
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
            
            $this->emit('postUpdated');       
        }else {
            $this->emit('postNotUpdated');
        }
        
    }
}
