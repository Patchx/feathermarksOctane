@extends('layouts.app')

@section('title', 'New Page')

@section('head_unique')
    <script src="https://cdn.tiny.cloud/1/qn0u6xfdgfz4vzfccdg8k1uewcqi4pjr1cjaurusuya1zd78/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            auto_focus: 'tiny-mce-target',
            height: 700,
            plugins: 'image link lists',
            selector: '#tiny-mce-target',
        });
    </script>
@endsection

@section('content')
<div class="ml-30 mr-30">
    <div class="row">
        <div class="col-12">
            <div 
                class="mx-auto" 
                style="max-width:1050px"
            >
                <form 
                    action="/pages/new-html"
                    method="post"
                >
                    @csrf

                    <input
                        name="category_id"
                        type="hidden"
                        value="{{$active_category->custom_id}}"
                    />

                    <h3 class="mb-20 mt-10 text-center">
                        <span>New Page</span>

                        <button 
                            class="btn btn-primary btm-sm inline-block float-right"
                            type="submit"
                        >Next &raquo;</button>
                    </h3>

                    <textarea 
                        id="tiny-mce-target"
                        name="page_html"
                    >{{session('new_page_html')}}</textarea>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection