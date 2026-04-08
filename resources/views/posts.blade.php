@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <!--  Live Post Counter -->
    <h5 class="mb-3">Total Posts: <span id="post-count">{{ count($posts) }}</span></h5>

    <!-- Create Post -->
    <div class="card mb-4">
        <div class="card-header">Create Post</div>
        <div class="card-body">
            <form method="POST" action="{{ route('posts.store') }}">
                @csrf
                <input type="text" name="title" placeholder="Title" class="form-control mb-2" required>
                <textarea name="body" placeholder="Body" class="form-control mb-2" required></textarea>
                <button class="btn btn-primary">Create Post</button>
            </form>
        </div>
    </div>

    <!-- Posts Table -->
    <div class="card">
        <div class="card-header">All Posts</div>
        <div class="card-body">
            <table class="table table-bordered" id="posts-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Body</th>
                        <th>User</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr id="post-{{ $post->id }}">
                            <td>{{ $post->id }}</td>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->body }}</td>
                            <td>{{ $post->user->name }}</td>
                            <td>{{ $post->created_at->format('d-m-Y H:i') }}</td>
                            <td>
                                <form method="POST" action="{{ route('posts.delete', $post->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('script')
<script>

console.log("JS Loaded ✅");

//  REAL-TIME CREATE
window.Echo.channel('posts')
.listen('.create', (e) => {

    console.log('New post arrived', e.post);

    const tableBody = document.querySelector('#posts-table tbody');

    const newRow = `
        <tr id="post-${e.post.id}">
            <td>${e.post.id}</td>
            <td>${e.post.title}</td>
            <td>${e.post.body}</td>
            <td>${e.post.user ? e.post.user.name : 'User'}</td>
            <td>${new Date(e.post.created_at).toLocaleString()}</td>
            <td><button class="btn btn-danger btn-sm">Delete</button></td>
        </tr>
    `;

    tableBody.insertAdjacentHTML('beforeend', newRow);

    // Counter update
    let count = document.getElementById('post-count');
    count.innerText = parseInt(count.innerText) + 1;
});

// REAL-TIME DELETE
window.Echo.channel('posts')
.listen('.delete', (e) => {

    console.log('Post deleted', e.postId);

    document.getElementById(`post-${e.postId}`)?.remove();

    let count = document.getElementById('post-count');
    count.innerText = parseInt(count.innerText) - 1;
});

</script>
@endsection