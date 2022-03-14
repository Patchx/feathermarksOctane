@extends('layouts.blog')

@section('title', 'Feathermarks Blog')

@section('head_unique')
@endsection

@section('content')
	<h1 class="blog-title text-center text-muted">Blog Articles</h1>

	<div class="blog-content">
		<h4 class="mx-auto text-center text-muted">
			<a href="/blog/set-feathermarks-as-the-new-tab-page-in-brave">Set Feathermarks as the new tab page in Brave</a>
		</h4>

		<br><br>
		
		<p class="blog-footer-feather mx-auto text-center text-muted">
			<i class="fas fa-feather-alt fa-3x"></i>
		</p>

		<br>
	</div>
@endsection