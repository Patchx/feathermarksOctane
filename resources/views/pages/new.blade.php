@extends('layouts.app')

@section('title', 'New Page')

@section('head_unique')
    <script src="https://cdn.tiny.cloud/1/qn0u6xfdgfz4vzfccdg8k1uewcqi4pjr1cjaurusuya1zd78/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
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
                    action="/pages/create"
                    method="post"
                >
                    @csrf

                    <div 
                        class="mx-auto" 
                        style="max-width:500px"
                    >
                        <p>
                            <label class="page-editor-label mr-20">Category:</label>

                            <select 
                                class="categories-dropdown form-control inline-block"
                                name="category"
                            >
                                @foreach($categories as $category)
                                    <option
                                        value="{{$category->custom_id}}"
                                        @if($category->custom_id === $active_category->custom_id)
                                            selected="true"
                                        @endif 
                                    >{{ucwords($category->name)}}</option>
                                @endforeach
                            </select>
                        </p>

                        <p>
                            <label class="page-editor-label mr-20">Page Name:</label>

                            <input
                                class="form-control inline-block"
                                name="page_name"
                                style="max-width:300px;"
                            />
                        </p>

                        <p>
                            <label class="page-editor-label mr-20">Search Keywords:</label>

                            <input
                                class="form-control inline-block"
                                name="search_keywords"
                                style="max-width:300px;"
                            />
                        </p>

                        <p>
                            <label class="page-editor-label mr-20">Instaopen Command:</label>

                            <input
                                class="form-control inline-block"
                                name="instaopen_command"
                                style="max-width:300px;"
                            />
                        </p>

                        <p>
                            <label class="page-editor-label mr-20">Access:</label>

                            <select 
                                class="form-control inline-block"
                                name="visibility_level"
                                style="width:165px"
                            >
                                <option
                                    value="anyone"
                                >Anyone can view</option>

                                <option
                                    value="me_only"
                                >Only I can see</option>
                            </select>
                        </p>
                    </div>

                    <br>

                    <textarea id="tiny-mce-target">Hello, World!</textarea>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection