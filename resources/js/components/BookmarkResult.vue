<template>
    <div>
        <div 
            class="inline-block align-top"
            style="margin-top:7px;"
        >
            <i
                v-on:click="$root.deleteLink(bookmark.custom_id)" 
                class="fas fa-trash text-muted cursor-pointer mr-20"
                style="font-size:18px"
            ></i>

            <i
                v-on:click="$root.openLinkEditor(bookmark)" 
                class="fas fa-edit text-muted cursor-pointer mr-10"
                style="font-size:18px"
            ></i>

            <img
                class="mr-10"
                :src="faviconUrl"
                height="24"
            />
        </div>

        <div class="inline-block">
            <span
                v-on:click="openBookmarkUrl"
                v-on:keyup.enter="openBookmarkUrl"
                class="cursor-pointer btn-link bookmark-result"
                style="font-size:24px"
                tabindex="0"
            >{{bookmark.name}}</span>

            <span
                v-if="bookmark.instaopen_command !== ''"
                v-cloak
            >
                <br>
                <span class="text-muted">
                    <span>Instaopen Command:&nbsp;</span>
                    <strong class="code-style">/{{bookmark.instaopen_command}}</strong>
                </span>
            </span>
        </div>
    </div>
</template>

<script>

// ---------------------
// - Private Functions -
// ---------------------

function trackLinkClick(this_component, callback) {
    axios.post('/links/track-click', {
        link_id: this_component.bookmark.custom_id,
    }).then((response) => {
        return callback();
    }).catch((error) => {
        console.log(error);
        return callback();
    });
}

// ----------
// - Export -
// ----------

module.exports = (function() {
    return {
        props: ['bookmark'],

        data: function() {
            return {};
        },

        computed: {
            faviconUrl: function() {
                return (
                    'https://www.google.com/s2/favicons?sz=32&domain_url='
                    + this.bookmark.url
                );
            },
        },

        methods: {
            openBookmarkUrl: function(event) {
                trackLinkClick(this, () => {
                    if (event.ctrlKey
                        || event.metaKey
                    ) {
                        return window.open(this.bookmark.url, '_blank');
                    }

                    window.location.href = this.bookmark.url;
                });
            },
        },
    };
})();
</script>
