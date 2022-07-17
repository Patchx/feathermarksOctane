@extends('layouts.app')

@section('title', $html_title)

@section('head_unique')
@endsection

@section('content')
<div class="text-center text-muted">
	<h3 class="mt-20">Categories</h3>
	<br>

    @if(session('success_msg'))
        <div 
            class="alert alert-success mb-20 mx-auto"
            style="max-width:200px;"
        >{{session('success_msg')}}</div>
    @endif

	<form
		action="/categories"
        class="mx-auto" 
		method="post"
        style="max-width:250px"
	>
		@csrf

		@foreach($categories as $category)
			<h4 class="mb-20">
				<input
					@if($loop->first)
						autofocus
					@endif
					maxlength="20"
					name="category-{{$category->custom_id}}"
					style="
						color:#555;
						padding-left: 10px;
						width:100%;
					"
					value="{{$category->name}}"
				/>
			</h4>
		@endforeach

		<button
            class="btn btn-primary"
            style="width:100%"
            type="submit"
		>Save</button>
	</form>
</div>
@endsection

@section('scripts')
@endsection
