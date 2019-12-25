<template>
    <div class="card-columns mt-2">
        <div v-for="photo in photoList" v-bind:key="photoList.id">
            <photo v-bind:photo="photo"/>
        </div>
    </div>
</template>
<script>
    import Photo from "./Photo";

    export default {
        props: {
            photoList: {type: Array, required: true}
        },
        components: {
            'photo': Photo
        }
    }
</script>
