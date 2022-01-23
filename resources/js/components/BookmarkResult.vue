<template>
    <div>
        <i
            v-on:click="$root.deleteLink(bookmark.custom_id)" 
            class="fas fa-trash text-muted cursor-pointer mr-25"
            style="font-size:18px"
        ></i>

        <i
            v-on:click="$root.openLinkEditor(bookmark)" 
            class="fas fa-edit text-muted cursor-pointer mr-25"
            style="font-size:18px"
        ></i>

        <a
            :href="bookmark.url"
            style="font-size:24px"
        >{{bookmark.name}}</a>

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
</template>

<script>
module.exports = (function() {
    return {
        props: ['bookmark'],

        data: function() {
            return {};
        },

        methods: {},
    };
})();
</script>
