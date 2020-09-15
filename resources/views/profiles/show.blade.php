@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card card-body bg-transparent border-0">
            <h4 class="card-title">
                {{ $user->name }} joined since {{ $user->created }}
            </h4>
            <hr>
        </div>
        @forelse ($activities as $date => $activity)
            <h4 class="px-4 pb-2">{{ $date }}</h4>
            <div class="px-5">
                @foreach ($activity as $record)
                    @if (view()->exists("profiles.activities.{$record->type}"))
                        @include("profiles.activities.{$record->type}",['activity' => $record])
                    @endif
                @endforeach
            </div>
        @empty
            <article>
                <p class="px-4 pb-2">No Feed</p>
            </article>
        @endforelse
    </div>
@endsection