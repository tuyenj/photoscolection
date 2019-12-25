<template>
    <b-card class="p-2 shadow-sm">
        <b-img fluid :src="photo.image_path"></b-img>
    </b-card>
</template>
<script>
    export default {
        props: {
            photo: {type: Object, required: true}
        },
    }
</script>
