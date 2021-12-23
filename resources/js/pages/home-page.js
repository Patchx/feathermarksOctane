
// ----------------
// - Dependencies -
// ----------------

import edit_link_modal from '../components/EditLinkModal';

// -----------------
// - Main Function -
// -----------------

(() => {
	// ---------------------
	// - Private Functions -
	// ---------------------

	function createLink(vue_app) {
		const params = new URLSearchParams(window.location.search);
		var category_id = params.get('cat_id');

		axios.post('/links/create', {
			name: vue_app.draft_bookmark.name,
			url: vue_app.draft_bookmark.url,
			search_phrase: vue_app.draft_bookmark.search_phrase,
			instaopen_command: vue_app.draft_bookmark.instaopen_command,
			category_id: category_id,
		}).then((response) => {
			if (response.data.status !== 'success') {
				console.log(response);
				alert("There was an error saving your bookmark. Please refresh the page and try again");
			}

			vue_app.created_bookmark = response.data.link;
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

	function detectFeatherCommand(vue_app) {
		if (vue_app.main_input_text.trim() === '//a') {
			vue_app.main_input_text = '';
			return fetchAllBookmarks(vue_app);
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

	function fetchAllBookmarks(vue_app) {
		const params = new URLSearchParams(window.location.search);
		var category_id = params.get('cat_id');
		var request_url = '/links/my-links';

		if (category_id !== null) {
			request_url += '?cat_id=' + category_id;
		}

		axios.get(request_url).then((response) => {
			vue_app.visible_bookmarks = response.data.links;
		});
	}

	function getBookmarkCreationTitle(vue_app) {
		if (vue_app.draft_bookmark.url === '') {
			return 'New Bookmark: Enter URL';
		}

		if (vue_app.draft_bookmark.name === '') {
			return 'Name this bookmark';
		}

		if (vue_app.draft_bookmark.search_phrase === '') {
			return 'Add a search phrase to help find this later';
		}

		return 'Add an instaopen command (optional)';
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
	};

	function handleAddBookmarkSubmission(vue_app) {
		if (vue_app.draft_bookmark.url === '') {
			vue_app.draft_bookmark.url = vue_app.main_input_text;
			var url = vue_app.main_input_text;
			vue_app.main_input_text = '';
			
			return getTitleFromUrl(url, (title) => {
				if (vue_app.main_input_text.length < 1) {
					return vue_app.main_input_text = title;
				}
			});
		}

		if (vue_app.draft_bookmark.name === '') {
			vue_app.draft_bookmark.name = vue_app.main_input_text;
			return vue_app.main_input_text = '';
		}

		if (vue_app.draft_bookmark.search_phrase === '') {
			vue_app.draft_bookmark.search_phrase = vue_app.main_input_text;
			return vue_app.main_input_text = '';
		}

		vue_app.draft_bookmark.instaopen_command = vue_app.main_input_text;
		vue_app.main_input_text = '';
		createLink(vue_app);
	}

	function searchMyLinks(vue_app) {
		const params = new URLSearchParams(window.location.search);
		var category_id = params.get('cat_id');
		
		var request_url = '/links/search-my-links?q=' 
							+ vue_app.main_input_text
							+ '&cat_id=' + category_id;

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
			created_bookmark: null,

			draft_bookmark: {
				url: '',
				name: '',
				search_phrase: '',
				instaopen_command: '',
			},

			main_input_text: '',
			mode: 'search',

			search_result_bookmarks: [],
			temporary_msg: '',
			visible_bookmarks: [],
		},

		components: {
			'edit-link-modal': edit_link_modal,
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

			searchIframeSrc: function() {
				return "/search-results?q=" + this.main_input_text;
			},

			showExternalSearchResults: function() {
				if (this.mode !== 'search') {
					return false;
				}

				return this.main_input_text !== '';
			},
		},

		mounted: function() {
			document.getElementById("search-bar").focus();
		},

		watch: {
			main_input_text: function(after, before) {
				if (after.length > 0) {
					this.temporary_msg = '';
					this.created_bookmark = null;

					if (this.mode === 'search') {
						debouncedSearchMyLinks(this);
					}
				}

				if (after.length > 0
					&& after[0] !== '/'
					&& this.mode === 'feather'
				) {
					this.activateSearchMode(false);
				}

				if (after.length === 1
					&& after[0] === '/'
				) {
					this.mode = 'feather';
				}

				if (this.mode === 'feather') {
					detectFeatherCommand(this);
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

				this.visible_bookmarks = [];
				this.mode = 'search';
			},

			activateAddBookmarkMode: function() {
				if (this.mode === 'add-bookmark') {
					return this.searchBarEnterPressed();
				}

				this.draft_bookmark.url = '';
				this.draft_bookmark.name = '';
				this.draft_bookmark.search_phrase = '';
				this.draft_bookmark.instaopen_command = '';

				this.visible_bookmarks = [];
				this.mode = 'add-bookmark';
			},

			deleteLink: function(link_id) {
				this.created_bookmark = null;
				var request_url = '/links/delete/' + link_id;

				axios.post(request_url).then((response) => {
					if (response.data.status !== 'success') {
						console.log(response);
						alert('There was an error deleting your link. Please refresh the page and try again');
						return null;
					}

					this.search_result_bookmarks = [];
					fetchAllBookmarks(this);
				}).catch((error) => {
					alert(error.response.data.message);
				});
			},

			handleLinkEdited: function() {
				this.visible_bookmarks = [];
				this.main_input_text = '';
				this.mode = 'search';
				this.temporary_msg = 'Link edited successfully!';
			},

			openLinkEditor: function(bookmark_data) {
				this.$modal.show('edit-link-modal', {
					componentProps: bookmark_data,
				});
			},

			searchBarEnterPressed: function() {
				if (this.mode === 'add-bookmark') {
					return handleAddBookmarkSubmission(this);
				}

				if (this.mode === 'feather') {
					var command = this.main_input_text;
					this.main_input_text = '';
					const params = new URLSearchParams(window.location.search);
					var category_id = params.get('cat_id');

					axios.post('/links/run-feather-command', {
						command: command,
						category_id: category_id,
					}).then((response) => {
						if (response.data.status === 'command_not_found') {
							return null;
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
			},
		},
	});
})();