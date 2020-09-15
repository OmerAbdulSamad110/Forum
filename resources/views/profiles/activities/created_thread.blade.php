@component('profiles.activities.card')
    @slot('heading')
        <h5>
            {{ $user->name }} 
            published 
            <a href="{{ $activity->subjectable->path() }}">
                {{ $activity->subjectable->title }}
            </a>
        </h5>
        <strong>
            {{ $activity->subjectable->repliesCount }} 
            {{ Str::plural('reply', $activity->subjectable->repliesCount) }}
        </strong>
    @endslot
    @slot('body')
        {{ $activity->subjectable->body }}
    @endslot
@endcomponent