
// ----------------
// - Dependencies -
// ----------------

import bookmark_result_component from '../components/BookmarkResult';
import edit_link_modal from '../components/EditLinkModal';
import high_usage_link_component from '../components/HighUsageLink';

// -----------------
// - Main Function -
// -----------------

(() => {
	// ---------------------
	// - Private Functions -
	// ---------------------

	function createLink(vue_app) {
		axios.post('/links/create', {
			name: vue_app.draft_bookmark.name,
			url: vue_app.draft_bookmark.url,
			search_phrase: vue_app.draft_bookmark.search_phrase,
			instaopen_command: vue_app.draft_bookmark.instaopen_command,
			category_id: vue_app.category_id,
		}).then((response) => {
			if (response.data.status !== 'success') {
				console.log(response);
				alert("There was an error saving your bookmark. Please refresh the page and try again");
			}

			vue_app.created_bookmark = response.data.link;
			vue_app.draft_bookmark = getEmptyBookmark();
			vue_app.activateSearchMode(true);
		}).catch((error) => {
			alert(error.response.data.message);
		});
	}

	const debouncedSearchMyLinks = (() => {
		var timeout;

		return (vue_app) => {
			clearTimeout(timeout);
		
			timeout = setTimeout(() => {
				timeout = null;
				searchMyLinks(vue_app);
			}, 300);
		};
	})();

	function deletePage(page_id, vue_app) {
		var request_url = '/pages/delete/' + page_id;

		axios.post(request_url, {}).then((response) => {
			if (response.data.status !== 'success') {
				console.log(response);
				alert("There was an error deleting your page. Please refresh the page and try again");
			}

			vue_app.activateSearchMode(true);
		}).catch((error) => {
			alert(error.response.data.message);
		});
	}

	function detectFeatherCommand(vue_app) {
		if (vue_app.main_input_text.trim() === '//a') {
			vue_app.main_input_text = '';
			return displayAllBookmarks(vue_app);
		}

		if (vue_app.main_input_text.trim() === '//b') {
			vue_app.main_input_text = '';
			return vue_app.activateAddBookmarkMode();
		}

		if (vue_app.main_input_text.trim() === '//s') {
			vue_app.main_input_text = '';
			return vue_app.activateSearchMode(true);
		}
	}

	function displayAllBookmarks(vue_app) {
		var request_url = '/links/all-links/' + vue_app.category_id;
		vue_app.mode = 'search';
		vue_app.showing_loading_text = true;

		axios.get(request_url).then((response) => {
			if (response.data.status === 'success') {
				vue_app.search_result_bookmarks = response.data.links;
			}

			vue_app.showing_loading_text = false;
		}).catch((error) => {
			console.log(error);
			vue_app.showing_loading_text = false;
		});
	}

	function getBookmarkCreationTitle(vue_app) {
		if (vue_app.draft_bookmark.url === '') {
			return "New Bookmark: Enter URL - or type 'new' to create a new page!";
		}

		if (vue_app.draft_bookmark.name === '') {
			return 'Name this bookmark';
		}

		if (vue_app.draft_bookmark.search_phrase === '') {
			return 'Add search keywords to help find this later';
		}

		return 'Add an instaopen command (optional)';
	}

	function getEmptyBookmark() {
		return {
			url: '',
			name: '',
			search_phrase: '',
			instaopen_command: '',
		};
	}

	function getTitleFromUrl(input_url, callback) {
		var request_url = '/url/title/' + input_url;

		axios.get(request_url).then((response) => {
			if (response.data.status === 'success'
				&& callback !== undefined
			) {
				return callback(response.data.title);
			}
		}).catch((error) => {
			console.log(error);
		});
	}

	function goToPageCreator(vue_app) {
		return window.location.href = '/pages/new?cat_id=' + vue_app.category_id;
	}

	function handleAddBookmarkSubmission(vue_app) {
		var input_text = vue_app.main_input_text;
		vue_app.main_input_text = '';

		if (vue_app.draft_bookmark.search_phrase !== '') {
			vue_app.draft_bookmark.instaopen_command = input_text;
			return createLink(vue_app);
		}

		if (input_text === '') {
			return null;
		}

		if (vue_app.draft_bookmark.name !== '') {
			return vue_app.draft_bookmark.search_phrase = input_text;
		}

		if (vue_app.draft_bookmark.url !== '') {
			return vue_app.draft_bookmark.name = input_text;
		}

		if (input_text === 'new') {
			return goToPageCreator(vue_app);
		}

		vue_app.draft_bookmark.url = input_text;
		
		return getTitleFromUrl(input_text, (title) => {
			if (input_text.length < 1) {
				return vue_app.main_input_text = title;
			}
		});
	}

	function handleEnterPressedInSearch(vue_app) {
		if (vue_app.noBookmarksFound) {
			return vue_app.redirectToSearchEngine();
		}

		var bookmark = document.getElementsByClassName('bookmark-result')[0];

		if (!!bookmark) {
			return bookmark.focus();
		}
	}

	function loadFrequentlyUsedLinks(vue_app) {
		var request_url = '/links/frequently-used/' + vue_app.category_id;

		axios.get(request_url).then((response) => {
			if (!response.data.links) {
				return null;
			}

			vue_app.frequently_used_links = response.data.links;
		});
	}

	function runFeatherCommand(command, vue_app) {
		axios.post('/links/run-feather-command', {
			command: command,
			category_id: vue_app.category_id,
		}).then((response) => {
			if (response.data.status === 'command_not_found') {
				return vue_app.show_command_not_found = true;
			}

			if (response.data.status !== 'success') {
				console.log(response);
				alert("There was an error running that command. Please refresh the page and try again");
			}

			if (response.data.directive === 'open_link') {
				window.location.href = response.data.url;
			}
		}).catch((error) => {
			alert(error.response.data.message);
		});

	}

	function searchMyLinks(vue_app) {
		if (vue_app.main_input_text === '') {
			return null;
		}
		
		var request_url = '/links/search-my-links?q=' 
			+ vue_app.main_input_text
			+ '&cat_id=' + vue_app.category_id;

		axios.get(request_url).then((response) => {
			if (response.data.status === 'success') {
				vue_app.search_result_bookmarks = response.data.links;
			}
		}).catch((error) => {
			console.log(error);
		});
	}

	// ----------------
	// - Vue Instance -
	// ----------------

	var vue_app = new Vue({
		el: '#vue_app',

		data: {
			category_id: document.getElementById('active-category').value,
			created_bookmark: null,
			draft_bookmark: getEmptyBookmark(),
			frequently_used_links: [],
			main_input_text: '',
			mode: 'search',
			search_result_bookmarks: [],
			show_command_not_found: false,
			showing_loading_text: false,
			show_searchbar_prepend: false,
			temporary_msg: '',
		},

		components: {
			'bookmark-result': bookmark_result_component,
			'edit-link-modal': edit_link_modal,
			'high-usage-link': high_usage_link_component,
		},

		computed: {
			mainLabelText: function() {
				if (this.temporary_msg !== '') {
					return this.temporary_msg;
				}

				if (this.mode === 'add-bookmark') {
					return getBookmarkCreationTitle(this);
				}

				return 'Search';
			},

			noBookmarksFound: function() {
				if (this.main_input_text.length < 2) {
					return false;
				}

				return this.search_result_bookmarks.length < 1;
			},

			plusBtnClasses: function() {
				if (this.mode === 'add-bookmark'
					|| this.mode === 'feather'
				) {
					return 'btn btn-primary';
				}

				return 'btn btn-outline-primary';
			},

			searchBtnClasses: function() {
				if (this.mode === 'search') {
					return 'btn btn-primary';
				}

				return 'btn btn-outline-primary';
			},

			showFrequentlyUsedLinks: function() {
				if (this.main_input_text.length > 0) {
					return false;
				}

				return this.frequently_used_links.length > 0
			},

			showSearchbarPrepend: function() {
				if (this.draft_bookmark.url !== ''
					&& this.draft_bookmark.name !== ''
					&& this.draft_bookmark.search_phrase !== ''
				) {
					return true;
				}

				return false;
			},

			showShortcodeUseHint: function() {
				if (this.mode !== 'feather') {
					return false;
				}

				if (this.main_input_text.length < 2) {
					return false;
				}

				if (this.main_input_text[0] === '/'
					&& this.main_input_text[1] === '/'
				) {
					return false;
				}

				return true;
			},
		},

		mounted: function() {
			document.getElementById("search-bar").focus();
			loadFrequentlyUsedLinks(this);
		},

		watch: {
			main_input_text: function(after, before) {
				this.temporary_msg = '';
				this.created_bookmark = null;
				this.show_command_not_found = false;

				if (after.length < 2
					&& after[0] !== '/'
				) {
					return this.search_result_bookmarks = [];
				}

				if (after[0] !== '/'
					&& this.mode === 'feather'
				) {
					return this.activateSearchMode(false);
				}

				if (after[0] === '/') {
					this.mode = 'feather';
				}

				if (this.mode === 'search') {
					return debouncedSearchMyLinks(this);
				}

				if (this.mode === 'feather') {
					return detectFeatherCommand(this);
				}
			},
		},

		methods: {
			activateSearchMode: function(clear_searchbar) {
				if (this.mode === 'feather'
					&& clear_searchbar !== false
				) {
					this.main_input_text = '';
				}

				this.search_result_bookmarks = [];
				this.mode = 'search';
				document.getElementById("search-bar").focus();
			},

			activateAddBookmarkMode: function() {
				document.getElementById("search-bar").focus();

				if (this.mode === 'add-bookmark') {
					return this.searchBarEnterPressed();
				}

				this.draft_bookmark.url = '';
				this.draft_bookmark.name = '';
				this.draft_bookmark.search_phrase = '';
				this.draft_bookmark.instaopen_command = '';

				this.search_result_bookmarks = [];
				this.mode = 'add-bookmark';
			},

			deleteLink: function(link_id) {
				var confirmation_prompt = "Are you sure you want to delete that link?";

				if (confirm(confirmation_prompt) !== true) {
					return null;
				}

				this.created_bookmark = null;
				var request_url = '/links/delete/' + link_id;

				axios.post(request_url).then((response) => {
					if (response.data.status !== 'success') {
						console.log(response);
						alert('There was an error deleting your link. Please refresh the page and try again');
						return null;
					}

					this.search_result_bookmarks = [];

					if (response.data.deleted_my_page_link
						&& confirm("Do you want to delete the page as well? Anyone currently allowed to see that page will lose access.")
					) {
						deletePage(response.data.page_id, this);
					}
				}).catch((error) => {
					alert(error.response.data.message);
				});
			},

			handleLinkEdited: function() {
				this.search_result_bookmarks = [];
				this.main_input_text = '';
				this.mode = 'search';
				this.temporary_msg = 'Link edited successfully!';
			},

			openLinkEditor: function(bookmark_data) {
				this.$modal.show('edit-link-modal', {
					componentProps: bookmark_data,
				});
			},

			redirectToSearchEngine: function() {
				window.location.href = (
					'https://www.google.com/search?q=' 
					+ this.main_input_text
				);
			},

			searchBarEnterPressed: function() {
				if (this.mode === 'search') {
					return handleEnterPressedInSearch(this);
				}

				if (this.mode === 'add-bookmark') {
					return handleAddBookmarkSubmission(this);
				}

				if (this.mode === 'feather') {
					var command = this.main_input_text;
					this.main_input_text = '';
					return runFeatherCommand(command, this);
				}
			},
		},
	});
})();