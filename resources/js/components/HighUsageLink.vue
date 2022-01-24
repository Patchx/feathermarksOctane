<template>
    <div
        v-on:click="openBookmarkUrl"
        class="cursor-pointer btn-link inline-block text-center"
        style="
            height:  120px;
            padding: 10px;
            width: 120px;
        "
    >
        <div
            style="
                background-color: #3490dc;
                border-radius: 10px;
                color: white;
                font-size: 25px;
                padding: 20px 0px;
            "
        >{{defaultFaviconText}}</div>

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
            defaultFaviconText: function() {
                return (
                    this.bookmark.name[0].toUpperCase() 
                    + this.bookmark.name.substring(1,4).toLowerCase()
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
