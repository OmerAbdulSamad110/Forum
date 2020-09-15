@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-0">
                            <a href="/profile/{{ $thread->user->name }}" class="title">{{ $thread->user->name }}</a> 
                            posted 
                            {{ $thread->title }}
                        </h5>
                        @can('update', $thread)
                            <form action="/threads/{{ $thread->channel_id }}/{{ $thread->id }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-link p-0">
                                    Delete Thread
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    {{ $thread->body }}   
                </div>
            </div>
            <br>  
            @auth        
                <div id="new-reply"></div>
                <br>
            @endauth  
            {{-- @if ($replies) --}}
                <div id="replies"></div>
            {{-- @else
                <div class="card card-body">
                    <h5 class="card-text">No Replies</h5>
                </div>
            @endif --}}

            {{-- @foreach ($replies as $reply)
                @include('threads.reply')
            @endforeach
            {{ $replies->links() }} --}}

        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <p>This thread was published on 
                        {{ $thread->date }} by 
                        <a href="#">{{ $thread->user->name }}</a> 
                        and currently has {{ $thread->replies_count }} 
                        {{ Str::plural('reply', $thread->replies_count) }}.
                    </p>
                    @auth
                        <div id="sub-btn" active="{{ json_encode($thread->isSubscribed) }}"></div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        Replies({
            'threadId':'{{ $thread->id }}',
            'channelId':'{{ $thread->channel_id }}'});
        SubscribeBtn('{{ $thread->id }}');
        // $(document).on('click', '.edit', function() {
        //     if(prevEl!=null){
        //         softBlur();
        //         loadPBtns();
        //     }
        //     var reply = $(this).parent().siblings('.card-body');
        //     var body = reply.html();
        //     var textarea = $('<textarea>');
        //     prevData = body;
        //     textarea.addClass('px-3 pt-3 form-control');
        //     textarea.val(body);
        //     reply.replaceWith(textarea);
        //     textarea.focus();
        //     prevData = textarea.val();
        //     prevEl = textarea;
        //     loadNBtns();
        // });
        // function softBlur() { 
        //      var divEl = $('<div>');
        //      if(prevEl.siblings('span')){
        //         prevEl.siblings('span').remove();
        //     }
        //      prevEl.replaceWith(divEl);
        //      divEl.addClass('card-body');
        //      divEl.html(prevData);
        //      prevEl = null; 
        //      prevData = null;
        // }
        // function loadNBtns() {
        //       footer = prevEl.siblings('.card-footer');
        //       edit = footer.children('.edit');
        //       delet = footer.children('form');
        //       var save = edit;
        //       var cancel = delet;
        //       save.replaceWith(
        //           '<form class="save">'+
        //           '<button type="submit" class="btn btn-success btn-sm mr-1">Save</button>'+
        //           '</form>');
        //       cancel.replaceWith('<button type="submit" class="btn btn-secondary btn-sm mr-1 cancel">Cancel</button>');
        // }
        // function loadPBtns() {
        //     footer.children('form').replaceWith(edit);
        //     footer.children('.cancel').replaceWith(delet);
        //     edit = null;
        //     delet = null;
        //     footer = null;
        // }
        // $(document).on('click','.cancel',function (){
        //       softBlur();
        //       loadPBtns();
        // });
        // $(document).on('submit','.save',function (event) { 
        //       event.preventDefault();
        //       var body = prevEl.val();
        //       $.ajax({
        //           type: "put",
        //           url: "/replies/500",
        //           dataType: "json",
        //           data: {
        //               "body": body,
        //               "_token": "{{ csrf_token() }}"
        //           },
        //           success: function (response) {
        //               console.log(response);
        //               if(response.success){
        //                   prevData = body;
        //                   softBlur();
        //                   loadPBtns();
        //                   alert(response.success);
        //               }else{
        //                 prevEl.addClass('is-invalid');
        //                 $('<span class="px-2 pb-1 font-weight-bold invalid-feedback">'+
        //                     response.errors[0]+
        //                     '</span>').insertAfter(prevEl);
        //               }
        //           }
        //       });
        // });
    </script>
@endsection
