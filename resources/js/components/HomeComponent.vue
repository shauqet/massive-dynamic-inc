<template>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Home</h3>
            </div>
            <div class="card-body">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mx-2 mt-2">
                                <div>Name: </div>
                                <label>{{ authUserData.name }}</label>
                            </div>
                            <div class="form-group mx-2 mt-2">
                                <div>Username: </div>
                                <label>{{ authUserData.username }}</label>
                            </div>
                            <div class="form-group mx-2 mt-2">
                                <div>Email: </div>
                                <label>{{ authUserData.email }}</label>
                            </div>
                            <div class="form-group mx-2 mt-2">
                                <div>Role: </div>
                                <label v-if="authUserData.role===1">Administrator</label>
                                <label v-else-if="authUserData.role===2">Secretary</label>
                                <label v-else-if="authUserData.role===3">Client</label>
                            </div>
                            <div v-if="authUserData.role===1 || authUserData.role===2" class="form-group mx-2 mt-2">
                                <button class="btn btn-sm btn-info" @click="openEditModal(authUserData)">
                                    Edit
                                    <i class="fa fa-edit"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-6" v-if="authUserData.contact_persons.length">
                            <div class="form-group mx-2 mt-2">
                                <div>Contact persons: </div>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Username</th>
                                        <th class="text-center">Email</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="contactPerson in authUserData.contact_persons" :key="contactPerson.id">
                                        <td class="text-center">{{ contactPerson.name }}</td>
                                        <td class="text-center">{{ contactPerson.email }}</td>
                                        <td class="text-center">{{ contactPerson.phone_number }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <create-and-edit :users="users" />
    </div>
</template>

<script>
    import {EventBus} from "../libraries/event-bus";
    import CreateAndEdit from "./users/CreateAndEdit";
    import Spinner from "./Spinner";

    export default {
        components: { CreateAndEdit, Spinner },
        data() {
            return {
                pageIsLoading: true,
                authUserData: {},
                users: [],
            };
        },
        methods: {
            loadAdminInfo() {
                axios.get(`${window.base_url}/admin/admin-info`)
                    .then(response => {
                        this.authUserData = response.data[1];
                    }).catch(() => {
                    Swal.fire("Error!", "Error", "warning");
                });
            },
            openEditModal(user) {
                EventBus.$emit('open-edit-modal', user);
            },
        },
        watch: {
        },
        created() {
        },
        mounted() {
            this.$emit('loadBreadcrumbLink', {url: '/', pageName: 'Home'});
            this.loadAdminInfo();
            EventBus.$on('load-users', () => this.loadAdminInfo());
        }
    }
</script>
