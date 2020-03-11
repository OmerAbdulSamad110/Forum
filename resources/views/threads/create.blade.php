@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Threads</div>

                <div class="card-body">
                    <form action="/threads" method="post">
                        @csrf
                        <div class="form-group">
                            <input type="text" name="title" id="title" class="form-control" placeholder="Enter Title...">
                        </div>
                        <div class="form-group">
                            <textarea name="body" id="body" rows="8" class="form-control" placeholder="Enter Body..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Publish</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
