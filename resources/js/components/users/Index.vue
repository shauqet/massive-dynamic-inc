<template>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Users table</h3>
                <div class="float-right">
                    <form class="form-inline ml-3">
                        <div class="input-group input-group-sm">
                            <input class="form-control form-control-navbar"
                                   type="search"
                                   placeholder="Search"
                                   aria-label="Search"
                                   v-model="searchKeyword"
                                   @keyup="searchUsers" >
                            <div class="input-group-append">
                                <button class="search-button btn btn-navbar border border-muted" @click.prevent="">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card-body">
                <button class="btn btn-sm btn-primary mb-2 float-right" @click="openCreateModal">
                    Add new <i class="fa fa-plus-circle"></i>
                </button>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th class="text-center">Name</th>
                        <th class="text-center">Username</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Role</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="pageIsLoading">
                        <td class="text-center" colspan="9">
                            <spinner />
                        </td>
                    </tr>
                    <tr v-for="user in users" :key="user.id">
                        <td class="text-center">{{ user.name }}</td>
                        <td class="text-center">{{ user.username }}</td>
                        <td class="text-center">{{ user.email }}</td>
                        <td class="text-center" v-if="user.role===1">Administrator</td>
                        <td class="text-center" v-else-if="user.role===2">Secretary</td>
                        <td class="text-center" v-else-if="user.role===3">Client</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info" @click="openEditModal(user)">
                                Edit
                                <i class="fa fa-edit"></i>
                            </button>
                        </td>
                        <td class="text-center" v-if="authUserData.role===1">
                            <button class="btn btn-sm btn-danger" @click="deleteUser(user.id)">
                                Delete <i class="fa fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                <pagination v-show="searchMode" class="mt-2" align="right" show-disabled :data="usersPagination" :limit="1" @pagination-change-page="loadSearched">
                    <span slot="prev-nav">Previous</span>
                    <span slot="next-nav">Next</span>
                </pagination>
                <pagination v-show="!searchMode" class="mt-2" align="right" show-disabled :data="usersPagination" :limit="1" @pagination-change-page="loadUsers">
                    <span slot="prev-nav">Previous</span>
                    <span slot="next-nav">Next</span>
                </pagination>
            </div>
        </div>
        <create-and-edit :users="users" />
    </div>
</template>

<script>
    import CreateAndEdit from "./CreateAndEdit.vue";
    import Spinner from "../Spinner";
    import { EventBus } from '../../libraries/event-bus.js';
    import {swalSuccess} from "../../libraries/my-libs";
    export default {
        components: { CreateAndEdit, Spinner },
        data() {
            return {
                pageIsLoading: true,
                searchMode: false,
                searchKeyword: "",
                users: [],
                usersPagination: {},
                authUserData: {},
            };
        },
        created() {
        },
        methods: {
            loadUsers(page = 1) {
                axios.get(`${window.base_url}/admin/users?page=${page}`).then(response => {
                    if(response.data[0] === "success") {
                        this.usersPagination = response.data[1];
                        this.users = response.data[1].data;
                        this.pageIsLoading = false;
                    }
                });
            },
            loadSearched(page = 1) {
                axios.get(`${window.base_url}/admin/users/${this.searchKeyword}/search?page=${page}`).then(response => {
                    if(response.data[0] === "success") {
                        this.usersPagination = response.data[1];
                        this.users = response.data[1].data;
                    }
                });
            },
            searchUsers() {
                if(!(this.searchKeyword === "")) {
                    this.searchMode = true;
                    axios.get(`${window.base_url}/admin/users/${this.searchKeyword}/search`).then(response => {
                            if(response.data[0] === "success") {
                                this.usersPagination = response.data[1];
                                this.users = response.data[1].data;
                            }
                        });
                }
                else {
                    this.searchMode = false;
                    this.loadUsers();
                }
            },
            openCreateModal() {
                EventBus.$emit('open-create-modal');
            },
            openEditModal(user) {
                EventBus.$emit('open-edit-modal', user);
            },
            deleteUser(id) {
                Swal.fire({
                    icon: "warning",
                    title: "Delete!",
                    text: "Are you sure you want to delete the user?",
                    showCancelButton: true,
                    cancelButtonColor: "#3085d6",
                    confirmButtonColor: "#c82333",
                    cancelButtonText: "Cancel",
                    confirmButtonText: "Delete"
                }).then((result) => {
                    if(result.value) {
                        axios.delete(`${window.base_url}/admin/users/${id}`)
                            .then(response => {
                                if(response.data[0] === "success") {
                                    swalSuccess("user deleted.");
                                    this.loadUsers();
                                }
                            }).catch(error => {
                                console.log(error)
                            Swal.fire("Error!", "There was an error", "warning");
                        });
                    }
                })
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
            this.loadUsers();
            this.loadAdminInfo();
            this.$emit('loadBreadcrumbLink', {url: '/user', pageName: 'Users'});
            EventBus.$on('load-users', () => this.loadUsers());
        }
    }
</script>
