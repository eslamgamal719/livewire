<div>

<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex">
                <b>Posts</b>
                <a href="javascript:void(0);" wire:click="create_post" class="btn btn-primary btn-sm ml-auto">Create Post</a>
            </div>

            <div class="card-body">

        @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-message">
                {{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('message_error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert-message">
                {{ session('message_error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

            @if($this->showCreateForm)
                <livewire:dynamic.create />
            @endif

            @if($this->showEditForm)
                <livewire:dynamic.edit />
            @endif

            @if($this->showPostForm)
                <livewire:dynamic.show />
            @endif

                <div class="table-responsive">
                 <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Owner</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                            <tr>
                                <td>
                                    @if($post->image != '')
                                    <img src="{{asset('assets/images/' . $post->image)}}" width="100" alt="{{ $post->title }}">
                                    @endif
                                </td>
                                <td><a href="javascript:void(0);" wire:click="show_post({{ $post->id }})">{{ $post->title }}</a></td>
                                <td>{{ $post->user->name }}</td>
                                <td>{{ $post->category->name }}</td>
                                <td>
                                    <a href="javascript:void(0);" wire:click="edit_post({{ $post->id }})" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="javascript:void(0);" wire:click="delete_post({{ $post->id }})" class="btn btn-danger btn-sm" onclick="confirm('Are You Sure ?');  return false; ">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                <div class="float-right">
                    {!! $posts->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>

</div>


@push('script')

<script>
$(function () {
    $("#alert-message").fadeTo(5000, 500).slideUp(500, function () {
        $('#alert-message').slideUp(500);
    });
});
</script>

@endpush