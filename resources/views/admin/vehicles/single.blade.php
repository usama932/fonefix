<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
    <tr>
        <th>Title</th>
        <td>{{ $article->title }}</td>
    </tr>
    <tr>
        <th>Slug</th>
        <td>{{ $article->slug }}</td>
    </tr>
    <tr>
        <th>Author</th>
        <td>{{ $article->user->name}}</td>
    </tr>

    <tr>
        <th>Content</th>
        <td>{{$article->content}} </td>
    </tr>

    <tr>
        <th>LENGTH</th>
        <td><img src="{{asset('uploads/article/'.$article->featured_image)}}"
                 style=" width:20px !important; height:50px !important;margin-top:12px" alt="Image is not found."/></td>
    </tr>
    <tr>
        <th>Categories</th>
        <td>
            @foreach($article->categories as $cat)
                <span class="tag label label-primary" style="padding: 2px">{{ $cat->name }}</span>
            @endforeach
        </td>
    </tr>


</table>


