<?php

namespace App\Http\Livewire\Dynamic;

use App\Category;
use App\Post;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithPagination;
    
    public $showCreateForm = false;
    public $showEditForm = false;
    public $showPostForm = false;

    protected $listeners = [
        'postAdded' => 'refreshCreatePosts',
        'postUpdated' => 'refreshUpdatedPosts',
        'postNotUpdated' => 'refreshNotUpdatedPosts',
        'postDeleted' => 'refreshDeletedPosts',
        'postNotDeleted' => 'refreshNotDeletedPosts'
    ];

  
    public function render()
    {
        $posts = Post::with(['user', 'category'])->orderBy('id', 'desc')->paginate(5);
        return view('livewire.dynamic.posts', [
            'posts' => $posts
        ]);
    }



    public function create_post() 
    {
        $this->showCreateForm = !$this->showCreateForm;
        $this->showEditForm = false;
        $this->showPostForm = false;
    }



    public function show_post($id) 
    {
        $post = Post::with(['user', 'category'])->whereId($id)->first();
        if($post) {
            $this->emit('showPost', $post);
            $this->showPostForm = !$this->showPostForm;
            $this->showEditForm = false;
            $this->showCreateForm = false;
        }
    }



    public function edit_post($id) 
    {
        $post = Post::whereId($id)->whereUserId(auth()->id())->first();
        if($post) {
            $this->emit('getPost', $post);  //post = array
            $this->showEditForm = !$this->showEditForm;
            $this->showCreateForm = false;
            $this->showPostForm = false;
        }
    }



    public function delete_post($id) 
    {
        $post = Post::whereId($id)->whereUserId(auth()->id())->first();
        if($post) {
            if(File::exists("assets/images/" . $post->image)) {
                unlink("assets/images/" . $post->image);
            }
            $post->delete();
            $this->emit('postDeleted');
        }else {
            $this->emit('postNotDeleted');
        }
    }



    public function refreshCreatePosts() 
    {
        session()->flash('message', 'Post Added Successfully');
        $this->showEditForm = false;
        $this->showCreateForm = false;
        $this->showPostForm = false;
    }

    public function refreshUpdatedPosts() 
    {
        session()->flash('message', 'Post Updated Successfully');
        $this->showEditForm = false;
        $this->showCreateForm = false;
        $this->showPostForm = false;
    }

    public function refreshNotUpdatedPosts() 
    {
        session()->flash('message_error', 'You Can not update not yours!');
        $this->showEditForm = false;
        $this->showCreateForm = false;
        $this->showPostForm = false;
    }


    public function refreshDeletedPosts() 
    {
        session()->flash('message', 'Post Deleted Successfully');
        $this->showEditForm = false;
        $this->showCreateForm = false;
        $this->showPostForm = false;
    }

    public function refreshNotDeletedPosts() 
    {
        session()->flash('message_error', 'You Can not delete not yours!');
        $this->showEditForm = false;
        $this->showCreateForm = false;
        $this->showPostForm = false;
    }



    
}
