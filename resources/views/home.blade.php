@extends('layouts.app')

@section('title', $html_title)

@section('head_unique')
@endsection

@section('content')
{{-- Modals --}}
<edit-link-modal
    v-on:saved="handleLinkEdited"
></edit-link-modal>
{{-- End Modals --}}

<div 
    id="homepage_content"
    class="ml-30 mr-30 mt-20"
>
    <input
        type="hidden"
        id="active-category"
        value="{{$active_category->custom_id}}"
    />

    @if(session('success_msg'))
        <div 
            class="alert alert-success mb-20 mx-auto"
            style="max-width:200px;"
        >{{session('success_msg')}}</div>
    @endif

    <div class="row">
        <div class="col-12">
            <div 
                class="mx-auto"
                style="max-width:550px"
            >
                <label>@{{mainLabelText}}</label>

                <div class="input-group">
                    <div 
                        v-if="showSearchbarPrepend"
                        v-cloak
                        class="input-group-prepend"
                    >
                        <span 
                            class="input-group-text" 
                            id="basic-addon1"
                        >/</span>
                    </div>

                    <input 
                        autofocus
                        class="form-control" 
                        id="search-bar"
                        v-on:keyup.enter="searchBarEnterPressed"
                        v-model="main_input_text"
                        type="text"
                    />

                    <div class="input-group-append">
                        <button 
                            :class="plusBtnClasses" 
                            tabindex="-1"
                            type="button"
                            v-on:click="activateAddBookmarkMode"
                        >
                            <i 
                                v-if="mode === 'add-bookmark'"
                                v-cloak
                                class="fas fa-arrow-right"
                            ></i>

                            <i 
                                v-else-if="mode === 'feather'"
                                v-cloak
                                class="fas fa-feather-alt"
                            ></i>

                            <i 
                                v-else
                                v-cloak
                                class="fas fa-plus"
                            ></i>
                        </button>
                    
                        <button 
                            v-on:click="activateSearchMode"
                            :class="searchBtnClasses" 
                            tabindex="-1"
                            type="button"
                        >
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div 
        v-if="mode !== 'feather' && search_result_bookmarks.length > 0"
        v-cloak
        class="row mt-30"
    >
        <div 
            class="mx-auto"
            style="max-width:600px"
        >
            <p>My Bookmarks</p>

            <p v-for="bookmark in search_result_bookmarks">
                <bookmark-result
                    :bookmark="bookmark"
                ></bookmark-result>
            </p>
        </div>
    </div>

    <div
        v-if="noBookmarksFound && mode === 'search'"
        v-cloak
        class="row mt-30"
    >
        <div 
            class="mx-auto"
            style="max-width:600px"
        >
            <p>
                <span>No Bookmarks Found. Press 'Enter' or </span>
                <span 
                    v-on:click="redirectToSearchEngine"
                    class="text-primary cursor-pointer"
                >click here</span>
                <span> to try Google Search.</span>
            </p>
        </div>
    </div>

    <div
        v-if="showShortcodeUseHint"
        v-cloak
        class="row mt-30"
    >
        <div 
            class="mx-auto"
            style="max-width:600px"
        >
            <p>Press 'Enter' to run your shortcode</p>
        </div>
    </div>

    <div
        v-if="show_command_not_found"
        v-cloak
        class="row mt-30"
    >
        <div 
            class="mx-auto"
            style="max-width:600px"
        >
            <p>Command not found</p>
        </div>
    </div>

    <div
        v-if="created_bookmark !== null && mode === 'search'"
        v-cloak
        class="row mt-30"
    >
        <div 
            class="mx-auto"
            style="max-width:400px"
        >
            <p>New Bookmark</p>

            <bookmark-result
                :bookmark="created_bookmark"
            ></bookmark-result>
        </div>
    </div>

    <div class="row justify-content-center mt-25">
        <div
            v-if="main_input_text.length < 2"
            v-cloak
        >
            <span
                v-if="mode !== 'add-bookmark'"
                v-cloak
            >
                <span>Type&nbsp;</span>
                <span class="code-style">//b</span>
                <span>&nbsp; to create a new bookmark</span>
            </span>
                        
            <span
                v-if="mode !== 'search'"
                v-cloak
            >
                <span>Type&nbsp;</span>
                <span class="code-style">//s</span>
                <span>&nbsp; to switch back to search mode</span>
            </span>
            <br>
        </div>
    </div>

    <div
        v-if="showFrequentlyUsedLinks"
        v-cloak
    >
        <div class="row mt-30 justify-content-center">
            <p>Frequently Used Links</p>
        </div>

        <div class="row justify-content-center">
            <high-usage-link
                :bookmark="bookmark"
                :key="index"
                v-for="(bookmark, index) in this.frequently_used_links"
            ></high-usage-link>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ mix('/wp/js/home-page.js') }}"></script>
@endsection
