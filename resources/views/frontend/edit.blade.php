@extends('layouts.app')
@section('content')

<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex">
                <b>Edit Post ({{ $post->title }})</b>
                <a href="{{ route('posts.index') }}" class="btn btn-primary btn-sm ml-auto">Posts</a>
            </div>
            <div class="card-body">

                <form action="{{ route('posts.update', $post->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" value="{{ old('title', $post->title) }}" class="form-control">
                        @error('title')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="category">Category</label>
                        <select name="category" class="form-control">
                            <option></option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category', $post->category_id) == $category->id ? "selected" : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('categroy')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="body">Body</label>
                        <textarea name="body" class="form-control" rows="5">{{ old('body', $post->body) }}</textarea>
                        @error('body')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>


                    <div class="form-group">
                        @if($post->image != '')
                            <img src="{{asset('assets/images/' . $post->image)}}" width="100">
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" name="image" class="custom-file">
                        @error('image')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="text-center">
                        <input type="submit" name="save" value="Update" class="btn btn-primary">
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection