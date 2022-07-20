<template>
    <div class="modal fade" id="create-and-edit-modal" tabindex="-1" role="dialog" aria-labelledby="create-and-edit-modal-label" aria-hidden="true" ref="createAndEditModalRef">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-show="!editmode" class="modal-title" id="create-and-edit-modal-label">Add new</h5>
                    <h5 v-show="editmode" class="modal-title" id="create-and-edit-modal-label">Edit data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateUser() : storeUser()">
                    <div class="modal-body">
                        <div class="form-group mx-2 mt-2">
                            <label for="name">Name *</label>
                            <input id="name"
                                   class="form-control"
                                   type="text"
                                   name="name"
                                   placeholder="Name"
                                   :class="{ 'border border-danger': userErrors.nameErrorPresent }"
                                   v-model="usersForm.name">
                            <small class="text-danger" v-if="userErrors.nameErrorPresent">
                                {{ userErrors.name }}
                            </small>
                        </div>
                        <div class="form-group mx-2 mt-2">
                            <label for="username">Username *</label>
                            <input id="username"
                                   class="form-control"
                                   type="text"
                                   name="username"
                                   placeholder="Username"
                                   :class="{ 'border border-danger': userErrors.usernameErrorPresent }"
                                   v-model="usersForm.username">
                            <small class="text-danger" v-if="userErrors.usernameErrorPresent">
                                {{ userErrors.username }}
                            </small>
                        </div>
                        <div class="form-group mx-2 mt-2">
                            <label for="email">Email *</label>
                            <input id="email"
                                   class="form-control"
                                   type="text"
                                   name="email"
                                   placeholder="Email"
                                   :class="{ 'border border-danger': userErrors.emailErrorPresent }"
                                   v-model="usersForm.email">
                            <small class="text-danger" v-if="userErrors.emailErrorPresent">
                                {{ userErrors.email }}
                            </small>
                        </div>
                        <div class="form-group mx-2 mt-2">
                            <label for="role">Role *</label>
                            <select id="role"
                                   class="form-control"
                                   type="text"
                                   :class="{ 'border border-danger': userErrors.roleErrorPresent }"
                                   v-model="usersForm.role">
                                <option value="1" v-if="authUserData.role===1">Administrator</option>
                                <option value="2" v-if="authUserData.role===1">Secretary</option>
                                <option value="3">Client</option>
                            </select>
                            <small class="text-danger" v-if="userErrors.roleErrorPresent">
                                {{ userErrors.role }}
                            </small>
                        </div>
                        <div class="form-group mx-2 mt-2" v-if="usersForm.contactPersons.length">
                            <label>Contact persons:</label>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Username</th>
                                    <th class="text-center">Email</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="contactPerson in usersForm.contactPersons" :key="contactPerson.id">
                                    <td class="text-center">{{ contactPerson.name }}</td>
                                    <td class="text-center">{{ contactPerson.email }}</td>
                                    <td class="text-center">{{ contactPerson.phone_number }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button id="updateUsersButton"
                                class="btn btn-primary"
                                type="submit"
                                v-show="editmode"
                                @click="storeUpdateDisabled = true">
                            Save
                            <span class="spinner-border-sm"
                                  role="status"
                                  aria-hidden="true"
                                  :class="{ 'spinner-border': storeUpdateDisabled }"></span>
                        </button>
                        <button id="storeUser"
                                class="btn btn-primary"
                                type="submit"
                                v-show="!editmode"
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
                editmode: true,
                cities: [],
                usersForm: {
                    id: "",
                    name: "",
                    username: "",
                    email: "",
                    role: "",
                    contactPersons: "",
                },
                userErrors: {
                    name: "",
                    nameErrorPresent: false,
                    username: "",
                    usernameErrorPresent: false,
                    email: "",
                    emailErrorPresent: false,
                    role: "",
                    roleErrorPresent: false
                }
            };
        },
        methods: {
            createUser() {
                this.editmode = false;
                this.resetUserErrors();
                this.resetUserForm();
                $('#create-and-edit-modal').modal('show');
            },
            storeUser() {
                this.resetUserErrors();
                const formData = new FormData();
                formData.append("id", this.usersForm.id);
                formData.append("name", this.usersForm.name);
                formData.append("username", this.usersForm.username);
                formData.append("email", this.usersForm.email);
                formData.append("role", this.usersForm.role);

                const config = { headers: { 'content-type': 'multipart/form-data' } };
                axios.post(`${window.base_url}/admin/users`, formData, config)
                    .then((response) => {
                        this.storeUpdateDisabled = false;
                        if(response.data[0] === "success") {
                            $("#create-and-edit-modal").modal("hide");
                            EventBus.$emit('load-users');
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
            editUser(user) {
                this.editmode = true;
                this.resetUserForm();
                this.resetUserErrors();
                this.fillUsersForm(user);
                $('#create-and-edit-modal').modal("show");
            },
            updateUser() {
                this.resetUserErrors();
                const formData = new FormData();
                formData.append("id", this.usersForm.id);
                formData.append("name", this.usersForm.name);
                formData.append("username", this.usersForm.username);
                formData.append("email", this.usersForm.email);
                formData.append("role", this.usersForm.role);
                formData.append("_method", "PATCH");

                const config = { headers: { "content-type": "multipart/form-data" } };
                axios.post(`${window.base_url}/admin/users/${this.usersForm.id}`, formData, config)
                    .then(response => {
                        this.storeUpdateDisabled = false;
                        if(response.data[0] === "success") {
                            $("#create-and-edit-modal").modal("hide");
                            swalSuccess("Success.");
                            EventBus.$emit('load-users');
                        }
                    }).catch(error => {
                    this.storeUpdateDisabled = false;
                    if (error.response.status === 422) {
                        this.checkForValidationErrors(error.response.data.errors);
                    }
                });
            },
            fillUsersForm(user) {
                this.usersForm.id = user.id;
                this.usersForm.name = user.name;
                this.usersForm.username = user.username;
                this.usersForm.email = user.email;
                this.usersForm.role = user.role;
                this.usersForm.contactPersons = user.contact_persons;
            },
            resetUserForm() {
                this.usersForm.id = "";
                this.usersForm.name = "";
                this.usersForm.username = "";
                this.usersForm.email = "";
                this.usersForm.role = "";
                this.usersForm.contactPersons = {};
            },
            resetUserErrors() {
                this.userErrors.name = "";
                this.userErrors.nameErrorPresent = false;
                this.userErrors.username = "";
                this.userErrors.usernameErrorPresent = false;
                this.userErrors.email = "";
                this.userErrors.emailErrorPresent = false;
                this.userErrors.role = "";
                this.userErrors.roleErrorPresent = false;
            },
            checkForValidationErrors(errors) {
                if(errors.hasOwnProperty("name")) {
                    this.userErrors.name = errors["name"][0];
                    this.userErrors.nameErrorPresent = true;
                }
                if(errors.hasOwnProperty("username")) {
                    this.userErrors.username = errors["username"][0];
                    this.userErrors.usernameErrorPresent = true;
                }
                if(errors.hasOwnProperty("email")) {
                    this.userErrors.email = errors["email"][0];
                    this.userErrors.emailErrorPresent = true;
                }
                if(errors.hasOwnProperty("role")) {
                    this.userErrors.role = errors["role"][0];
                    this.userErrors.roleErrorPresent = true;
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
            EventBus.$on('open-create-modal', () => this.createUser());
            EventBus.$on('open-edit-modal', user => this.editUser(user));
            $(this.$refs.createAndEditModalRef).on("hidden.bs.modal", this.clearData);
            this.loadAdminInfo();
        }
    }
</script>

<style scoped>
    .modal-body .invalid-tab {
        color: #dc3545;
        background-color: #F8D3D7;
    }
</style>
