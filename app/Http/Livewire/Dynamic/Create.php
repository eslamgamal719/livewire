<?php

namespace App\Http\Livewire\Dynamic;

use App\Post;
use App\Category;
use Livewire\Component;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Livewire\WithFileUploads;

class Create extends Component
{

    use WithFileUploads;

    public $title;
    public $body;
    public $category;
    public $image;



    public function render()
    {
        return view('livewire.dynamic.create', [
            'categories' => Category::all()
        ]);
    }


    public function save() 
    {
        $this->validate([
            'title' => 'required|max:255',
            'category' => 'required',
            'body' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif|max:20000'
        ]);

        $data['user_id']      = auth()->id();
        $data['title']        = $this->title;
        $data['body']         = $this->body;
        $data['category_id']  = $this->category;

        if($image = $this->image) {
            $fileName = Str::slug($this->title) . '.' . $image->getClientOriginalExtension();
            $path = public_path('/assets/images/' . $fileName);

            Image::make($image->getRealPath())->save($path, 100);

            $data['image'] = $fileName;
        }

        Post::create($data);

        $this->resetInputs();

        $this->emit('postAdded');
    }


    private function resetInputs() 
    {
        $this->title    = null;
        $this->category = null;
        $this->body     = null;
        $this->image    = null;
    }
}
