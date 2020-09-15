@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center m-4">
        <div class="col-md-10">
            @include('threads._list')
            {{ $threads->links() }}
        </div>
    </div>
</div>
@endsection
