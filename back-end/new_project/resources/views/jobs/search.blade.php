<div>
    @foreach ($jobs as $job)
    <div >
        <div>{{ $job->title }}</div>
        <div>{{ $job->category->name }}</div>
        <div>{{ $job->category->id }}</div>
        <hr>
    </div>
    @endforeach
</div>
