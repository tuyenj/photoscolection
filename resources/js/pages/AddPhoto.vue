<template>
    <b-card header="写真のアップロード">
        <b-alert class="mt-5" :show="dismissSecs" variant="success" fade v-if="success !== ''">
            <p>{{success}}</p>
        </b-alert>
        <b-row>
            <b-col cols="7">
                <b-form-group>
                    <b-form-file id="file-default" ref="fileInput" v-on:change="fileUpload" placeholder="写真ファイルを選んでください"></b-form-file>
                    <div v-if="errors !== null">
                        <p class="text-danger" v-for="img in errors.image">{{img}}</p>
                    </div>
                    <button class="btn btn-success mt-4" @click.prevent="photoUpload">Upload</button>
                </b-form-group>
            </b-col>
            <b-col cols="5" v-if="previewULR !== ''">
                <b-img class="w-auto" thumbnail fluid :src="previewULR"></b-img>
            </b-col>
        </b-row>
    </b-card>
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
        created() {
            this.success = '';
        },
        methods: {
            fileUpload(e) {
                this.errors = null;
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
