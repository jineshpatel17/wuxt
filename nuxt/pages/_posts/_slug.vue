<template>
    <div>
        <div class="container">
            <div class="post">
                <h2 v-if='post.title' class="mb-8">{{ post.title.rendered }}</h2>
                <div v-if='post.content' v-html="post.content.rendered"></div>
            </div>
        </div>
    </div>
</template>

<script>

    import axios from 'axios'
    import fetch from 'isomorphic-fetch'
    

    export default {
        data() {
            // if not
            return {
                post:{}
            }
        },
        // created() {
        //     console.log('test', this.$nuxt._route.params)
        //     fetch('http://docker.dev.jellyfish.net:3080/wp-json/wp/v2/posts/?slug='+this.$route.params.slug+'&status=publish').then((res) => {
        //         console.log('testing',   res)
        //         this.post = res.json
        //         console.log(res.json)
        //     })
        // },
        async asyncData({params, payload}) {
            
            if (payload) {
                return {
                    post: payload
                }
            } else {
                return axios.get('http://docker.dev.jellyfish.net:3080/wp-json/wp/v2/posts/?slug='+params.slug+'&status=publish').then((res) => {
                console.log('test', res)
                    return {
                        post: res.data[0]
                    }
                })
                .catch((error) => {
                    return { error: error }
                })
            }
        }, 
        // methods: {
        //     async visibilityChanged(isVisible) {
        //         if (isVisible) {
        //             let apiUrl = 'wp/v2/pages/?slug='+post.slug+'&status=publish';
        //             let res = {};

        //             res = await this.$apiCall(apiUrl);
        //             this.apiResponse(res);
        //         }
        //     },
        //     apiResponse(response) {
        //         return {
        //             post: res.data
        //         }
        //     }
        // }
    }


</script>