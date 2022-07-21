<template>
    <div class="modal fade" id="create-and-edit-modal" tabindex="-1" role="dialog" aria-labelledby="create-and-edit-modal-label" aria-hidden="true" ref="createAndEditModalRef">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-show="!editMode" class="modal-title" id="create-and-edit-modal-label">Add new</h5>
                    <h5 v-show="editMode" class="modal-title" id="create-and-edit-modal-label">Edit data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? updateContactPersons() : storeContactPersons()">
                    <div class="modal-body">
                        <div class="form-group mx-2 mt-2">
                            <label for="name">Name *</label>
                            <input id="name"
                                   class="form-control"
                                   type="text"
                                   name="name"
                                   placeholder="Name"
                                   :class="{ 'border border-danger':contactPersonsErrors.nameErrorPresent }"
                                   v-model="contactPersonsForm.name">
                            <small class="text-danger" v-if="contactPersonsErrors.nameErrorPresent">
                                {{contactPersonsErrors.name }}
                            </small>
                        </div>
                        <div class="form-group mx-2 mt-2">
                            <label for="email">Email *</label>
                            <input id="email"
                                   class="form-control"
                                   type="text"
                                   name="email"
                                   placeholder="Email"
                                   :class="{ 'border border-danger':contactPersonsErrors.emailErrorPresent }"
                                   v-model="contactPersonsForm.email">
                            <small class="text-danger" v-if="contactPersonsErrors.emailErrorPresent">
                                {{contactPersonsErrors.email }}
                            </small>
                        </div>
                        <div class="form-group mx-2 mt-2">
                            <label for="phone_number">Phone Number *</label>
                            <input id="phone_number"
                                   class="form-control"
                                   type="text"
                                   name="phone_number"
                                   placeholder="Phone Number"
                                   :class="{ 'border border-danger':contactPersonsErrors.phoneNumberErrorPresent }"
                                   v-model="contactPersonsForm.phoneNumber">
                            <small class="text-danger" v-if="contactPersonsErrors.phoneNumberErrorPresent">
                                {{contactPersonsErrors.phoneNumber }}
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button id="updateContactPersonssButton"
                                class="btn btn-primary"
                                type="submit"
                                v-show="editMode"
                                @click="storeUpdateDisabled = true">
                            Save
                            <span class="spinner-border-sm"
                                  role="status"
                                  aria-hidden="true"
                                  :class="{ 'spinner-border': storeUpdateDisabled }"></span>
                        </button>
                        <button id="storeContactPersons"
                                class="btn btn-primary"
                                type="submit"
                                v-show="!editMode"
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
                editMode: true,
                contactPersonsForm: {
                    id: "",
                    name: "",
                    email: "",
                    phoneNumber: "",
                },
               contactPersonsErrors: {
                    name: "",
                    nameErrorPresent: false,
                    email: "",
                    emailErrorPresent: false,
                    phoneNumber: "",
                    phoneNumberErrorPresent: false
                }
            };
        },
        methods: {
            createContactPersons() {
                this.editMode = false;
                this.resetContactPersonErrors();
                this.resetContactPersonForm();
                $('#create-and-edit-modal').modal('show');
            },
            storeContactPersons() {
                this.resetContactPersonErrors();
                const formData = new FormData();
                formData.append("id", this.contactPersonsForm.id);
                formData.append("name", this.contactPersonsForm.name);
                formData.append("email", this.contactPersonsForm.email);
                formData.append("phone_number", this.contactPersonsForm.phoneNumber);
                formData.append("user_id", this.$route.params.id);

                const config = { headers: { 'content-type': 'multipart/form-data' } };
                axios.post(`${window.base_url}/admin/contact-persons`, formData, config)
                    .then((response) => {
                        this.storeUpdateDisabled = false;
                        if(response.data[0] === "success") {
                            $("#create-and-edit-modal").modal("hide");
                            EventBus.$emit('load-contact-persons');
                            swalSuccess("Success.");
                        }
                    }).catch(error => {
                    this.storeUpdateDisabled = false;
                    if (error.response.status === 422) {
                        this.checkForValidationErrors(error.response.data.errors);
                    }
                });
            },
            editContactPerson(contactPerson) {
                this.editMode = true;
                this.resetContactPersonForm();
                this.resetContactPersonErrors();
                this.fillContactPersonsForm(contactPerson);
                $('#create-and-edit-modal').modal("show");
            },
            updateContactPersons() {
                this.resetContactPersonErrors();
                const formData = new FormData();
                formData.append("id", this.contactPersonsForm.id);
                formData.append("name", this.contactPersonsForm.name);
                formData.append("email", this.contactPersonsForm.email);
                formData.append("phone_number", this.contactPersonsForm.phoneNumber);
                formData.append("user_id", this.$route.params.id);
                formData.append("_method", "PATCH");

                const config = { headers: { "content-type": "multipart/form-data" } };
                axios.post(`${window.base_url}/admin/contact-persons/${this.contactPersonsForm.id}`, formData, config)
                    .then(response => {
                        this.storeUpdateDisabled = false;
                        if(response.data[0] === "success") {
                            $("#create-and-edit-modal").modal("hide");
                            swalSuccess("Success.");
                            EventBus.$emit('load-contact-persons');
                        }
                    }).catch(error => {
                    this.storeUpdateDisabled = false;
                    if (error.response.status === 422) {
                        this.checkForValidationErrors(error.response.data.errors);
                    }
                });
            },
            fillContactPersonsForm(contactPerson) {
                this.contactPersonsForm.id = contactPerson.id;
                this.contactPersonsForm.name = contactPerson.name;
                this.contactPersonsForm.email = contactPerson.email;
                this.contactPersonsForm.phoneNumber = contactPerson.phone_number;
            },
            resetContactPersonForm() {
                this.contactPersonsForm.id = "";
                this.contactPersonsForm.name = "";
                this.contactPersonsForm.email = "";
                this.contactPersonsForm.phoneNumber = "";
            },
            resetContactPersonErrors() {
                this.contactPersonsErrors.name = "";
                this.contactPersonsErrors.nameErrorPresent = false;
                this.contactPersonsErrors.email = "";
                this.contactPersonsErrors.emailErrorPresent = false;
                this.contactPersonsErrors.phoneNumber = "";
                this.contactPersonsErrors.phoneNumberErrorPresent = false;
            },
            checkForValidationErrors(errors) {
                if(errors.hasOwnProperty("name")) {
                    this.contactPersonsErrors.name = errors["name"][0];
                    this.contactPersonsErrors.nameErrorPresent = true;
                }
                if(errors.hasOwnProperty("email")) {
                    this.contactPersonsErrors.email = errors["email"][0];
                    this.contactPersonsErrors.emailErrorPresent = true;
                }
                if(errors.hasOwnProperty("phone_number")) {
                    this.contactPersonsErrors.phoneNumber = errors["phone_number"][0];
                    this.contactPersonsErrors.phoneNumberErrorPresent = true;
                }
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
            EventBus.$on('open-create-modal', () => this.createContactPersons());
            EventBus.$on('open-edit-modal', contactPerson => this.editContactPerson(contactPerson));
            $(this.$refs.createAndEditModalRef).on("hidden.bs.modal", this.clearData);
            this.loadAdminInfo();
        }
    }
</script>
