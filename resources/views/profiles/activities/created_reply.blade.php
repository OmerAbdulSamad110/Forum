@component('profiles.activities.card')
    @slot('heading')
        <h5>
            {{ $user->name }} 
            replied on 
            <a href="{{ $activity->subjectable->thread->path() }}">
                {{ $activity->subjectable->thread->title }}
            </a>
        </h5>
    @endslot
    @slot('body')
        {{ $activity->subjectable->body }}
    @endslot
@endcomponent