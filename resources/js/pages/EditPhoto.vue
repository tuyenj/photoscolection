<template>
    <b-card header="写真の編集" v-if="image !== null">
        <b-alert :show="dismissSecs" variant="success" fade v-if="success !== ''">
            <p>{{success}}</p>
        </b-alert>
        <b-row>
            <b-col cols="7">
                <b-form-group>
                    <b-form-file id="file-default" ref="fileInput" :placeholder="image.image_name" v-on:change="changeImage"></b-form-file>
                    <div v-if="errors !== null">
                        <p class="text-danger" v-for="img in errors.image">{{img}}</p>
                    </div>
                    <button class="btn btn-success mt-4" v-on:click="update">Upload</button>
                    <router-link tag="button" class="btn btn-danger mt-4" to="/">Cancel</router-link>
                </b-form-group>
            </b-col>
            <b-col cols="5" v-if="previewURL !==''">
                <b-img class="w-auto" thumbnail fluid :src="previewURL"></b-img>
            </b-col>
        </b-row>
    </b-card>
</template>
<script>
    import {OK, VALIDATION_ERROR} from "../const/httpStatus";

    export default {
        data() {
            return {
                previewURL: '',
                dismissSecs:5,
                success:'',
                errors:null,
                image:{
                    image_path:'',
                    image_name:''
                }
            }
        },
        created(){
            this.fetchData();
        },
        methods:{
            async fetchData(){
                const response = await axios.get(`/api/photo/${this.$route.params.id}`);
                const status = response.status;
                if (status !== OK){
                    this.$store.commit('error/setCode',status);
                }
                this.image = response.data;
                this.previewURL = this.image.image_path;
            },
            changeImage(e){
                this.image = e.target.files[0];
                this.previewURL = URL.createObjectURL(this.image);
            },
            async update(){
                this.success = '';
                this.errors = null;
                const formData = new FormData();
                formData.append('image',this.image);
                const response = await axios.post(`/api/photo/${this.$route.params.id}/edit`,formData);
                const status = response.status;
                if (status === OK){
                    this.success = 'ファイルのアップデートが完了。';
                    return false;
                }
                if (status === VALIDATION_ERROR) {
                    this.errors = response.data.errors;
                    return false;
                }
                this.$store.commit('error/setCode',status);
            }
        }
    }
</script>
