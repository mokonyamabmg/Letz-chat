<template>
    <div class="card activity-container">
        <div class="card-body">
            <h5 class="card-title text-bold">What People Are Talking About</h5>
            <hr />

            <md-card v-for="comment in mostCommented" class="mt-3">

                <md-card-content>
                    {{comment.title}}
                </md-card-content>

            </md-card>
        </div>
    </div>
</template>

<script>
export default {
    mounted() {
        console.log("Component mounted.");
    },
    data() {
        return {};
    },
    created() {
        this.$store.dispatch('fetchPosts');
    },
    computed: {
        mostCommented() {
            return this.$store.getters.getMostCommentedPosts;
        }
    }
};
</script>

<style lang="scss" scoped>
.activity-container {
    display: none;
    margin: 5vh 0;
     width: 15rem;
     min-height:80vh;
     overflow: auto;
}

@media (min-width: 72rem) {
    .activity-container {
        display: inline;
        margin: 5vh 0;
        min-height:80vh;
        overflow: auto
    }
}

</style>
