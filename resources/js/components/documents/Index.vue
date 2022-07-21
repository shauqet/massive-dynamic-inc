<template>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Documents table for client: <strong>{{ userInfo.name }}</strong></h3>
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
                        <th class="text-center">Title</th>
                        <th class="text-center">Document</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="pageIsLoading">
                        <td class="text-center" colspan="9">
                            <spinner />
                        </td>
                    </tr>
                    <tr v-for="document in documents" :key="document.id">
                        <td class="text-center">{{ document.path.substring(11) }}</td>
                        <td class="text-center">
                            <a :href="document.path" target="_blank">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                        <td class="text-center" v-if="authUserData.role===1">
                            <button class="btn btn-sm btn-danger" @click="deleteDocuments(document.id)">
                                Delete <i class="fa fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                <pagination class="mt-2" align="right" show-disabled :data="documentsPagination" :limit="1" @pagination-change-page="loadDocuments">
                    <span slot="prev-nav">Previous</span>
                    <span slot="next-nav">Next</span>
                </pagination>
            </div>
        </div>
        <create-and-edit :documents="documents" />
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
                documentsPagination: {},
                authUserData: {},
                userInfo: {},
                documents: [],
                clientName: [],
            };
        },
        methods: {
            loadDocuments(page = 1) {
                axios.get(`${window.base_url}/admin/documents-by-user-id/${this.$route.params.id}/?page=${page}`).then(response => {
                    if(response.data[0] === "success") {
                        this.documentsPagination = response.data[1];
                        this.documents = response.data[1].data;
                        this.pageIsLoading = false;
                    }
                });
            },
            openCreateModal() {
                EventBus.$emit('open-create-modal');
            },
            openEditModal(user) {
                EventBus.$emit('open-edit-modal', user);
            },
            deleteDocuments(id) {
                Swal.fire({
                    icon: "warning",
                    title: "Delete!",
                    text: "Are you sure you want to delete the document?",
                    showCancelButton: true,
                    cancelButtonColor: "#3085d6",
                    confirmButtonColor: "#c82333",
                    cancelButtonText: "Cancel",
                    confirmButtonText: "Delete"
                }).then((result) => {
                    if(result.value) {
                        axios.delete(`${window.base_url}/admin/documents/${id}`)
                            .then(response => {
                                if(response.data[0] === "success") {
                                    swalSuccess("Document deleted.");
                                    this.loadDocuments();
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
        created() {
            this.loadAdminInfo = axios.get(`${window.base_url}/admin/admin-info`)
                .then(response => {
                    this.authUserData = response.data[1];
                }).catch(() => {
                Swal.fire("Error!", "Error", "warning");
            });
        },
        async mounted() {
            await this.loadAdminInfo;
            this.loadDocuments();
            this.loadUserInfo();
            this.$emit('loadBreadcrumbLink', {url: '/user', pageName: 'Documents'});
            EventBus.$on('load-documents', () => this.loadDocuments());
            if(this.authUserData.role !==1){
                await this.$router.push('/admin')
            }
        }
    }
</script>
