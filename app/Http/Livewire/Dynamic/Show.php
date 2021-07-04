<?php

namespace App\Http\Livewire\Dynamic;

use Livewire\Component;

class Show extends Component
{

    public $post;
    public $post_id;
    public $title;
    public $body;
    public $category;
    public $user;
    public $image;

    protected $listeners = [
        'showPost' => 'show_post'
    ];


    public function render()
    {
        return view('livewire.dynamic.show');
    }



    public function show_post($post) 
    {
        $this->post = $post;
        $this->post_id = $this->post['id'];
        $this->title = $this->post['title'];
        $this->body = $this->post['body'];
        $this->user = $this->post['user']['name'];
        $this->category = $this->post['category']['name'];
        $this->image = $this->post['image'];
       
    }
}
