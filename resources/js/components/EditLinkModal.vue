<style>
    .edit-link-modal-p {
        margin-bottom: 5px;
    }
</style>

<template>
    <modal 
        name="edit-link-modal"
        v-on:before-open="handleBeforeOpen"
        v-on:opened="handleOnOpen"
        width="350"
        height="auto"
        :scrollable="true"
    >
        <div style="padding: 20px">
            <h1
                class="text-center"
                style="font-size: 22px"
            >
                <span>Edit Link</span>

                <span 
                    class="float-right text-muted cursor-pointer"
                    v-on:click="closeModal"
                >X</span>
            </h1>

            <div style="font-size: 20px">
                <p class="edit-link-modal-p">Name</p>

                <input
                    v-model="link_name"
                    class="form-control mb-15"
                    maxlength="100"
                    ref="name_input"
                />

                <p class="edit-link-modal-p">URL</p>

                <input
                    v-model="link_url"
                    class="form-control mb-15"
                />

                <p class="edit-link-modal-p">Search Keywords</p>

                <input
                    v-model="search_phrase"
                    class="form-control mb-15"
                    maxlength="100"
                />

                <p class="edit-link-modal-p">Instaopen Command</p>

                <input
                    v-model="instaopen_command"
                    class="form-control mb-15"
                    maxlength="10"
                />

                <button
                    v-on:click="submitForm"
                    class="btn btn-primary mt-15"
                    style="width: 100%"
                >Save</button>
            </div>
        </div>
    </modal>
</template>

<script>
module.exports = (function() {
    return {
        name: 'edit-link-modal',

        data: function() {
            return {
                instaopen_command: '',
                link_id: '',
                link_name: '',
                link_url: '',
                search_phrase: '',
            };
        },

        methods: {
            closeModal: function() {
                this.$modal.hide('edit-link-modal');
            },

            handleBeforeOpen: function(event) {
                const props = event.params.componentProps;
                this.instaopen_command = props.instaopen_command;
                this.link_id = props.custom_id;
                this.link_name = props.name;
                this.link_url = props.url;
                this.search_phrase = props.search_phrase;
            },

            handleOnOpen: function(event) {
                this.$refs.name_input.focus();
            },

            submitForm: function() {
                var url = '/links/edit/' + this.link_id;

                axios.post(url, {
                    name: this.link_name,
                    url: this.link_url,
                    search_phrase: this.search_phrase,
                    instaopen_command: this.instaopen_command,
                }).then((response) => {
                    this.$emit('saved');
                    this.closeModal();
                }).catch((error) => {
                    console.log(error);
                });
            },
        },
    };
})();
</script>
