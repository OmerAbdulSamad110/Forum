@forelse ($threads as $thread)
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <a href="{{ $thread->path() }}">
                        <h4 
                        class="mb-0 text-truncate" style="max-width: 30rem;">
                        @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                        <strong>{{ $thread->title }}</strong>
                        @else
                        {{ $thread->title }}
                        @endif
                        </h4>
                    </a>
                        Posted by <strong><a href="/profile/{{ $thread->user->name }}">{{ $thread->user->name }}</a></strong>
                </div>
                    <strong>{{ $thread->replies_count }} {{ Str::plural('reply', $thread->replies_count) }}</strong>
                </div>
        </div>
        <div class="card-body">
            <div class="card-text">{{ $thread->body }}</div>
        </div>
    </div>
@empty
    <div class="card card-body">
        <div class="card-text font-weight-bold">
            There are no relevant threads present...
        </div>
    </div>
@endforelse