@extends('layouts.blog')

@section('title', $html_title)

@section('head_unique')
@endsection

@section('content')
	<h1 class="blog-title text-center text-muted">Set Feathermarks as the new tab page in Brave</h1>

	<div class="blog-content">
		<p class="mx-auto text-muted">The privacy-focused Brave browser improves upon Chrome in numerous ways. Brave users will find the process of changing their New Tab page built into the Brave settings, unlike Chrome - which offloads this responsibility to 3rd party browser extensions. For iPhone users, the Brave Browser does not currently support changing the New Tab page, so you will need to bookmark Feathermarks.com instead if you want to have quick access to your Feathermarks bookmarks on that device.</p>

		<h4 class="mx-auto text-muted">For desktop/tablet devices:</h4>

		<p class="mx-auto text-muted">First you'll need to navigate to your settings page. Look for the <i class="fas fa-bars"></i> icon on the upper right side of the browser and click Settings, or copy and paste brave://settings/ into a new browser tab. Once you are on the settings page, scroll down until you see the 'Show home button' in the 'Appearance' section. You'll need to turn this setting to 'On' and set the homepage to https://feathermarks.com/home in order for the next steps to work correctly.</p>

		<img 
			src="https://res.cloudinary.com/feathermarks-com/image/upload/v1647208082/blog-articles/setting-new-tab-page/Brave/open-feathermarks-from-home-button_prctni.webp"
			class="blog-image"
		/>

		<p class="mx-auto text-muted">Next, scroll down to the 'New Tab Page' section of the settings page and change the new tab page to open your Homepage. Now, when you open a new tab, it will default to loading your homepage, which is now Feathermarks.com!</p>

		<img 
			src="https://res.cloudinary.com/feathermarks-com/image/upload/v1647208085/blog-articles/setting-new-tab-page/Brave/open-feathermarks-in-new-tab_r5zxg6.webp"
			class="blog-image"
		/>

		<p 
			class="mx-auto text-muted"
		>While on the Settings page, you can also set Brave to open Feathermarks.com when the browser first starts up. To set this, look for the 'On startup' option in the 'Get Started' section.</p>

		<img 
			src="https://res.cloudinary.com/feathermarks-com/image/upload/v1647208086/blog-articles/setting-new-tab-page/Brave/open-feathermarks-on-startup_ldf0r2.webp"
			class="blog-image"
		/>

		<img 
			src="https://res.cloudinary.com/feathermarks-com/image/upload/v1647208084/blog-articles/setting-new-tab-page/Brave/open-feathermarks-on-startup-2_sebzlr.webp"
			class="blog-image"
		/>

		<p 
			class="mx-auto text-muted"
		>Feathermarks.com is optimized to load as quickly as possible. However, if you find the pageload delay distracting, You can use <a href="https://c.feathermarks.com">https://c.feathermarks.com</a> as a 'cover page' when opening new tabs. This way, you'll be guarenteed to have a lightning fast new tab page, but still be able to access your Feathermarks bookmarks quickly and easily.</p>

		<br>
		
		<p class="blog-footer-feather mx-auto text-center text-muted">
			<i class="fas fa-feather-alt fa-3x"></i>
		</p>

		<br>
	</div>
@endsection