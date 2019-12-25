<template>
    <b-card header="写真のアップロード">
        <b-alert :show="dismissSecs" variant="success" fade v-if="success !== ''">
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
                    <router-link tag="button" class="btn btn-danger mt-4" to="/">Cancel</router-link>
                </b-form-group>
            </b-col>
            <b-col cols="5" v-if="previewURL !== ''">
                <b-img class="w-auto" thumbnail fluid :src="previewURL"></b-img>
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
                previewURL: '',
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
                this.previewURL = URL.createObjectURL(this.image);
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
