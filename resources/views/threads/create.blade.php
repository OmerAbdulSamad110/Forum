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
                            <select name="channel_id" id="channel_id" class="custom-select @error('channel_id') is-invalid @enderror" required>
                                <option value="">Choose Channel...</option>
                                @foreach ($channels as $channel)
                                    <option value="{{ $channel->id }}" {{ old('channel') == $channel->id ? 'selected' : '' }}>{{ $channel->name }}</option>
                                @endforeach
                            </select>
                            @error('channel_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input name="title" id="title" type="text" class="form-control @error('title') is-invalid @enderror" placeholder="Enter Body..." value="{{ old('title') }}" required>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <textarea name="body" id="body" rows="8" class="form-control @error('body') is-invalid @enderror" placeholder="Enter Body..." required>{{ old('body') }}</textarea>
                            @error('body')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary @if(count($errors)) btn-danger @endif">Publish</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
