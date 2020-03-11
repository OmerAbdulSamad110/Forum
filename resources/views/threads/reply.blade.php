<div class="card">
    <div class="card-header">
        <a href="/users/{{ $reply->user->id }}">{{ $reply->user->name }}</a> said 
        {{ $reply->date }}
    </div>
    <div class="card-body">
        {{ $reply->body }}  
    </div>
</div>
<br>