<template>
    <div v-if="photo !== null">
        <photo-list v-bind:photo-list="photo.data"/>
        <b-pagination
            v-model="photo.current_page"
            :total-rows="photo.total"
            :per-page="photo.per_page"
            aria-controls="my-table"
            @change="pageChange"
            align="center"
            class="mt-2"
        ></b-pagination>
    </div>
</template>
<script>
    import PhotoList from "../components/PhotoList";

    export default {
        data() {
            return {
                photo: null
            }
        },
        components: {'photo-list': PhotoList}
        ,
        created() {
            this.fetchData();
        },
        methods: {
            async fetchData() {
                const response = await axios.get(`/api/photos?page=${this.$route.query.page}`);
                const status = response.status;
                this.photo = response.data;
                if (this.photo.data.length === 0) {
                    await this.$router.push('/');
                    return false;
                }
            },
            async pageChange(currentNumber) {
                await this.$router.push({name: 'home-page', query: {page: currentNumber}})
                const res = await axios.get(`/api/photos?page=${this.$route.query.page}`);
                const status = res.status;
                this.photo = res.data;
            }
        }
    }
</script>
