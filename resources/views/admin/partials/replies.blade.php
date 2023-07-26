@foreach($comments as $comment)
    <div class="display-comment">

        <strong>{{ $comment->user->name }}</strong>
        <p>{!! $comment->comment !!}</p>
        @if($comment->attachment)
        <a href="{{asset("uploads/$comment->attachment")}}" class="btn btn-sm btn-info" target="_blank"><i class="flaticon-attachment"></i> {{$comment->attachment}}</a>
        @endif
        <a href="{{route("article-comment-delete",$comment->id)}}" id="" class="btn btn-sm btn-outline-danger py-0 pull-right mb-3" title="Delete Comment"><i class="fa fa-trash"></i></a>
        <hr>
        <a href="" id="reply"></a>

        @include('admin.partials.replies', ['comments' => $comment->replies])
    </div>
@endforeach
