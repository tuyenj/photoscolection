<template>
    <div class="card card-block min-vh-100">
        <b-alert class="mt-5" :show="dismissSecs" variant="success" fade v-if="success !== ''">
                <p>{{success}}</p>
        </b-alert>
        <b-col class="col-4">
            <b-img class="w-auto" thumbnail fluid :src="previewULR"></b-img>
        </b-col>
        <b-form-group label="Default:" label-for="file-default" label-cols-sm="2" class="mt-5 p-2">
            <b-form-file id="file-default" ref="fileInput" v-on:change="fileUpload"></b-form-file>
            <div v-if="errors !== null">
                <p class="text-danger" v-for="img in errors.image">{{img}}</p>
            </div>
            <button class="btn btn-success mt-2" @click.prevent="photoUpload">Upload</button>
        </b-form-group>

    </div>
</template>
<script>
    import {CREATED, VALIDATION_ERROR} from "../const/httpStatus";

    export default {
        data() {
            return {
                image: null,
                errors: null,
                success: '',
                count: 3000,
                previewULR: '',
                dismissSecs: 5
            }
        },
        created(){
            this.success ='';
        },
        methods: {
            fileUpload(e) {
                this.image = e.target.files[0];
                this.previewULR = URL.createObjectURL(this.image);
            },
            async photoUpload() {
                this.success = '';
                this.errors = null;
                const formData = new FormData();
                formData.append('image', this.image);
                const response = await axios.post('/api/photo/new', formData);
                const status = response.status;
                if (status === CREATED) {
                    this.success = 'ファイルのアップロードが完了。';
                }
                if (status === VALIDATION_ERROR) {
                    this.errors = response.data.errors;
                }
                // ERROR
                this.$store.commit("error/setCode", status);
            }
        }
    }
</script>
