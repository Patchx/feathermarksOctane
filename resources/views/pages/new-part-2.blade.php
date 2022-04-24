@extends('layouts.app')

@section('title', 'New Page')

@section('head_unique')
@endsection

@section('content')
<div class="ml-30 mr-30">
    <div class="row">
        <div class="col-12">
            <div 
                class="mx-auto" 
                style="max-width:1050px"
            >
                <h3 class="mb-20 mt-10 text-center">New Page</h3>

                <form 
                    action="/pages/create"
                    method="post"
                >
                    @csrf

                    <input
                        name="page_html"
                        type="hidden"
                        value="{{session('new_page_html')}}"
                    />

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
                                autofocus
                                class="form-control inline-block"
                                name="page_name"
                                required
                                style="max-width:300px;"
                            />
                        </p>

                        <p>
                            <label class="page-editor-label mr-20">Search Keywords:</label>

                            <input
                                class="form-control inline-block"
                                name="search_keywords"
                                required
                                style="max-width:300px;"
                            />
                        </p>

                        <p>
                            <label class="page-editor-label mr-20">Instaopen Command:</label>

                            <input
                                class="form-control inline-block"
                                name="instaopen_command"
                                placeholder="optional"
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

                    <div class="mt-30 text-center">
                        <a 
                            class="btn btn-outline-secondary inline-block mr-20 pl-20 pr-20"
                            href="/pages/new"
                        >Back</a>

                        <button 
                            class="btn btn-primary btm-sm inline-block pl-20 pr-20"
                            type="submit"
                        >Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection