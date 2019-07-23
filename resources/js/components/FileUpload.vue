<template>
    <div class="row">
        <!--FIle upload form-->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">File Upload</div>

                <div class="card-body">
                    <form action="" id="img-upload-form">

                        <div class="input-group mb-3">
                            <select class="form-control" v-model="batch">
                                <option>Select CSV Batch</option>
                                <option v-for="batch in csv_batches" :value="batch">{{ batch }}</option>
                            </select>
                        </div>

                        <div class="input-group">
                            <div class="custom-file">
                                <div id="select-file" @click="addFiles">
                                    <div>Select files ...</div>
                                </div>

                                <input type="file"
                                       id="files"
                                       ref="files"
                                       accept=".jpg,.jpeg,.tiff,.pdf"
                                       multiple
                                       @change="handleFilesUpload">
                            </div>
                        </div>
                        <div class="text-center" v-if="files.length">
                            Selected <strong>{{ files.length }}</strong> file<span v-if="files.length > 1">s</span>
                        </div>

                        <div class="text-center" id="submit">
                            <button class="btn btn-secondary" type="button" @click="submitFiles">Upload</button>
                        </div>

                        <div class="text-danger" v-if="errors.hasOwnProperty('files')">
                            <div v-for="file in errors.files">{{ file.message }}</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--Selected files list-->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Files List</div>

                <div class="card-body">
                    <div class="progress mb-4" v-if="uploadPercentage > 0">
                        <div class="progress-bar"
                             :style="{width: uploadPercentage+'%'}">{{ uploadPercentage }}%</div>
                    </div>
                    <div class="table-responsive" id="files-list">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>File</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(file, key) in files">
                                <td>{{ (key +1) }}</td>
                                <td>{{ file.name }}</td>
                                <td>
                                    <span class="remove-file" @click="removeFile(key)">Remove</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import axios from "axios";

    export default {
        props: ['errors', 'csv_batches'],
        data(){
            return {
                config: {       // Axios config
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                },
                submitUrl: '/img/store',    // submit to this url

                batch: 'Select CSV Batch',  // csv files batch
                files: [],      // selected files
                maxFilesLimit: 10000,   // maximum number of files allowed

                chunkSize: 3,   // files to send at once (files will be sent by chinking them)
                uploadPercentage: 0 // successful upload percentage
            }
        },


        mounted() {
        },


        methods: {

            // Adds file
            addFiles() {
                this.$refs.files.click();
            },

            // Submits files to the server
            submitFiles() {

                // prepared form data
                let formDataList = this.prepareFormData();

                // number or chunks sent to the server
                let submitCount = 0;

                // uploading every chunk of files
                formDataList.map((formData) => {

                    // Make the request to the POST /select-files URL
                    axios
                        .post(this.submitUrl, formData, this.config)
                        .then((res) => {
                            // incrementing chunk submit count
                            submitCount++;
                        })
                        .catch((err) => {
                            console.log('File submit error !!', err);
                        }).then(() => {
                            // update upload percentage
                            this.uploadPercentage = Math.round((submitCount / formDataList.length) * 100);

                            // clearing files when upload fully complete
                            if (this.uploadPercentage >= 100) {
                                this.files = [];
                                formDataList = [];
                            }
                        });
                });
            },

            // chunks and appends extra data to formData
            prepareFormData() {

                let chunks = _.chunk(this.files, this.chunkSize);

                // chucking files as sending very large amount of files is not good idea
                return chunks.map((chunk, index) => {

                    // Initialize the form data
                    let formData = new FormData();

                    // Iterate over any file sent over appending the files to the form data.
                    for( let i = 0; i < chunk.length; i++ ){
                        let file = chunk[i];

                        formData.append('files[' + i + ']', file);
                    }

                    // adding file batch to form data
                    formData.append('batch', this.batch);

                    return formData;
                });
            },

            // Handles the uploading of files
            handleFilesUpload() {
                let selectedFiles = this.$refs.files.files;

                // checking for max file limit
                if (selectedFiles.length > this.maxFilesLimit || this.files.length >= this.maxFilesLimit) {
                    alert(`Maximum of ${this.maxFilesLimit} files allowed.`);
                    return false;
                }

                // Adds the selected file to the files array
                for(let i = 0; i < selectedFiles.length; i++){
                    this.files.push( selectedFiles[i] );
                }
            },

            // Removes a select file the user has uploaded
            removeFile(key) {
                this.files.splice(key, 1);
            }
        }
    }
</script>

<style scoped>
    input[type="file"]{
        position: absolute;
        top: -500px;
    }

    span.remove-file{
        color: red;
        cursor: pointer;
        float: right;
    }

    #files-list {
        height: 300px;
        max-height: 300px;
    }

    #select-file {
        margin-top: 160px;
        height: 180px;
        width: 100%;
        border: 2px rgb(120, 120, 120) dashed;
        cursor: pointer;
        position: relative;;
    }

    #select-file div {
        text-align: center;
        vertical-align: middle;
        font-size: 30px;
        color: #8d8d8d;
        position: absolute;
        top: 38%;
        left: 25%;
    }

    #submit {
        margin-top: 175px;
    }
</style>
