<template>
    <div class="modal fade" id="create-and-edit-modal" tabindex="-1" role="dialog" aria-labelledby="create-and-edit-modal-label" aria-hidden="true" ref="createAndEditModalRef">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="create-and-edit-modal-label">Add new</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="storeDocuments()">
                    <div class="modal-body">
                        <div class="form-group mx-2 mt-2">
                            <label class="d-block" for="path">File * </label>
                            <div class="input-group mt-2" :class="{ 'border border-danger': contactPersonsErrors.pathErrorPresent }" style="border-radius: 0.25rem;">
                                <input id="path" :class="{ 'border border-danger': contactPersonsErrors.pathErrorPresent }" name="path" type="file" ref="fileupload" @change="onFileSelected">
                            </div>
                            <small class="text-danger" v-if="contactPersonsErrors.pathErrorPresent">
                                {{ contactPersonsErrors.path }}
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button id="storeDocuments"
                                class="btn btn-primary"
                                type="submit"
                                @click="storeUpdateDisabled = true">
                            Add
                            <span class="spinner-border-sm"
                                  role="status"
                                  aria-hidden="true"
                                  :class="{ 'spinner-border': storeUpdateDisabled }"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import { EventBus } from '../../libraries/event-bus.js';
    import { swalSuccess } from '../../libraries/my-libs.js';
    export default {
        data() {
            return {
                authUserData: {},
                storeUpdateDisabled: false,
                selectedFile: "",
                contactPersonsForm: {
                    id: "",
                    path: "",
                },
               contactPersonsErrors: {
                    path: "",
                    pathErrorPresent: false,
                }
            };
        },
        methods: {
            createDocuments() {
                this.editmode = false;
                this.resetDocumentErrors();
                this.resetDocumentForm();
                $('#create-and-edit-modal').modal('show');
            },
            storeDocuments() {
                this.resetDocumentErrors();
                const formData = new FormData();
                formData.append("id", this.contactPersonsForm.id);
                formData.append("path", this.contactPersonsForm.path);
                formData.append("user_id", this.$route.params.id);

                const config = { headers: { 'content-type': 'multipart/form-data' } };
                axios.post(`${window.base_url}/admin/documents`, formData, config)
                    .then((response) => {
                        this.storeUpdateDisabled = false;
                        if(response.data[0] === "success") {
                            $("#create-and-edit-modal").modal("hide");
                            EventBus.$emit('load-documents');
                            swalSuccess("Success.");
                        }
                    }).catch(error => {
                    this.storeUpdateDisabled = false;
                    console.log(error.response)
                    if (error.response.status === 422) {
                        this.checkForValidationErrors(error.response.data.errors);
                    }
                });
            },
            resetDocumentForm() {
                this.contactPersonsForm.path = "";
                this.$refs.fileupload.value=null;
            },
            resetDocumentErrors() {
                this.contactPersonsErrors.path = "";
                this.contactPersonsErrors.pathErrorPresent = false;
            },
            checkForValidationErrors(errors) {
                if(errors.hasOwnProperty("path")) {
                    this.contactPersonsErrors.path = errors["path"][0];
                    this.contactPersonsErrors.pathErrorPresent = true;
                }
            },
            onFileSelected(event){
                this.contactPersonsForm.path = event.target.files[0];
            },
            loadAdminInfo() {
                axios.get(`${window.base_url}/admin/admin-info`)
                    .then(response => {
                        this.authUserData = response.data[1];
                    }).catch(() => {
                    Swal.fire("Error!", "Error", "warning");
                });
            },
        },
        mounted() {
            EventBus.$on('open-create-modal', () => this.createDocuments());
            $(this.$refs.createAndEditModalRef).on("hidden.bs.modal", this.clearData);
            this.loadAdminInfo();
        }
    }
</script>
