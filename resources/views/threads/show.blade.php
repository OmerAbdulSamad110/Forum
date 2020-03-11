@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a href="/users/{{ $thread->user->id }}">{{ $thread->user->name }}</a> 
                    posted 
                    {{ $thread->title }}
                </div>

                <div class="card-body">
                    {{ $thread->body }}   
                </div>
            </div>
        </div>
    </div>
    <br>
    @if (auth()->check())        
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ $thread->path() }}/reply" method="post">
                @csrf
                <div class="form-group">
                    <textarea name="body" id="body" cols="3" rows="3" class="form-control" placeholder="Have something to say..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    <br>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach ($thread->replies as $reply)
            @include('threads.reply')
            @endforeach
        </div>
    </div>
</div>
@endsection
