@component('profiles.activities.card')
    @slot('heading')
        <h5>
            {{ $user->name }} 
            favorited a 
            <a href="{{ $activity->subjectable->favorable->path() }}">reply</a> 
            on 
            <a href="{{ $activity->subjectable->favorable->thread->path() }}">
                {{ $activity->subjectable->favorable->thread->title }}
            </a>
        </h5>
    @endslot
    @slot('body')
        {{ $activity->subjectable->favorable->body }}
    @endslot
@endcomponent