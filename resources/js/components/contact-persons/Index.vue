<template>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Contact persons table for client: <strong>{{ userInfo.name }}</strong></h3>
                <div class="float-right">
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
                        <th class="text-center">Email</th>
                        <th class="text-center">Phone number</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="pageIsLoading">
                        <td class="text-center" colspan="9">
                            <spinner />
                        </td>
                    </tr>
                    <tr v-for="contactPerson in contactPersons" :key="contactPerson.id">
                        <td class="text-center">{{ contactPerson.name }}</td>
                        <td class="text-center">{{ contactPerson.email }}
                        <td class="text-center">{{ contactPerson.phone_number }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info" @click="openEditModal(contactPerson)">
                                Edit
                                <i class="fa fa-edit"></i>
                            </button>
                        </td>
                        <td class="text-center" v-if="authUserData.role===1">
                            <button class="btn btn-sm btn-danger" @click="deleteContactPersons(contactPerson.id)">
                                Delete <i class="fa fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                <pagination class="mt-2" align="right" show-disabled :data="contactPersonsPagination" :limit="1" @pagination-change-page="loadContactPersons">
                    <span slot="prev-nav">Previous</span>
                    <span slot="next-nav">Next</span>
                </pagination>
            </div>
        </div>
        <create-and-edit :contact-persons="contactPersons" />
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
                contactPersonsPagination: {},
                authUserData: {},
                userInfo: {},
                contactPersons: [],
                clientName: [],
            };
        },
        created() {
        },
        methods: {
            loadContactPersons(page = 1) {
                axios.get(`${window.base_url}/admin/contact-persons-by-user-id/${this.$route.params.id}/?page=${page}`).then(response => {
                    if(response.data[0] === "success") {
                        this.contactPersonsPagination = response.data[1];
                        this.contactPersons = response.data[1].data;
                        this.pageIsLoading = false;
                    }
                });
            },
            openCreateModal() {
                EventBus.$emit('open-create-modal');
            },
            openEditModal(contactPerson) {
                EventBus.$emit('open-edit-modal', contactPerson);
            },
            deleteContactPersons(id) {
                Swal.fire({
                    icon: "warning",
                    title: "Delete!",
                    text: "Are you sure you want to delete the contact person?",
                    showCancelButton: true,
                    cancelButtonColor: "#3085d6",
                    confirmButtonColor: "#c82333",
                    cancelButtonText: "Cancel",
                    confirmButtonText: "Delete"
                }).then((result) => {
                    if(result.value) {
                        axios.delete(`${window.base_url}/admin/contact-persons/${id}`)
                            .then(response => {
                                if(response.data[0] === "success") {
                                    swalSuccess("contact person deleted.");
                                    this.loadContactPersons();
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
            loadUserInfo() {
                axios.get(`${window.base_url}/admin/users/${this.$route.params.id}`)
                    .then(response => {
                        this.userInfo = response.data[1];
                    }).catch(() => {
                    Swal.fire("Error!", "Error", "warning");
                });
            },
        },
        mounted() {
            this.loadContactPersons();
            this.loadAdminInfo();
            this.loadUserInfo();
            this.$emit('loadBreadcrumbLink', {url: '/user', pageName: 'Contact Persons'});
            EventBus.$on('load-contact-persons', () => this.loadContactPersons());
        }
    }
</script>
