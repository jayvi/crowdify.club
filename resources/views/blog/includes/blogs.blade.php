@foreach($blogs as $blog)
    @include('blog.includes.blog_post')
@endforeach
{!! $blogs->render() !!}