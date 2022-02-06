<template>
    <div
        v-on:click="openBookmarkUrl"
        v-on:keyup.enter="openBookmarkUrl"
        class="cursor-pointer btn-link inline-block text-center"
        style="
            height:  120px;
            padding: 10px;
            width: 120px;
        "
        tabindex="0"
    >
        <img
            class="mb-10"
            :src="faviconUrl"
            width="60"
        />

        <div
            style="font-size: 18px"
        >{{bookmark.name.substring(0,10)}}</div>
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
                    'https://www.google.com/s2/favicons?sz=64&domain_url='
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
