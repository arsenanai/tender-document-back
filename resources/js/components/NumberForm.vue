<template>
    <h1>Posts</h1>
    <div v-if="posts">
        <h1>{{ posts.title }}</h1>
        <h4>{{ posts.id }}</h4>
        <p>{{ posts.body }}</p>
    </div>
    <p v-else>Loading...</p>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            posts: null,
        };
    },
    created() {
        this.fetchPosts();
    },
    methods: {
        fetchPosts() {
            const postId = this.$route.params.id;

            axios
                .get("https://jsonplaceholder.typicode.com/posts")
                .then((response) => {
                    // Filter posts based on the userId that matches postId
                    const filteredPosts = response.data.filter(
                        (post) => post.id == postId
                    );

                    this.posts = filteredPosts[0];

                    console.log(filteredPosts[0]);
                })

                .catch((error) => {
                    console.error("Error fetching posts:", error);
                });
        },
    },
};
</script>
