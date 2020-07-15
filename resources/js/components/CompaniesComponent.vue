<template>
    <div class="container">
        <a @click="getCurrentCompany({
            id: '',
            name: '',
            logo: '',
            email: '',
            website: ''
        })"  href="#companyModal" class="btn btn-primary float-right mb-2" data-toggle="modal">Add Company</a>
        <table class="table bg-white">
            <thead>
                <tr>
                    <th scope="col">Companies</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody v-for="company in companies" v-bind:key="company.id">
                <tr>
                    <td>
                        <img v-bind:src="`${base_url}/storage/${company.logo}`">
                        <br/>
                        <strong>{{company.name}}</strong>
                        <br/>
                        <a v-bind:href="`mailto:${company.email}`">{{company.email}}</a>
                        <br/>
                        <a v-bind:href="company.website">{{company.website}}</a>
                    </td>
                    <td>    
                        <a v-bind:href="`${base_url}/company/${company.id}`" class="btn btn-secondary">Employees</a>
                        <a @click="getCurrentCompany(company)" href="#companyModal" class="btn btn-success" data-toggle="modal">Edit</a>
                        <button @click="deleteCompany(company.id)" class="btn btn-danger">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div id="companyModal" class="modal fade">
            <div class="modal-dialog">
                <form class="modal-content" @submit.prevent>
                    
                    <div class="modal-header">						
                        <h4 class="modal-title">Add Company</h4>                        
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">					
                        <div class="form-group">
                            <label>Logo</label>
                            <input type="file" ref="file" class="form-control-file" v-on:change="fileHandler()">
                            <div class="text-danger" v-if="errors.file">{{errors.file[0]}}</div>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="company.name">
                            <div class="text-danger" v-if="errors.name">{{errors.name[0]}}</div>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control" v-model="company.email">
                        </div>
                        <div class="form-group">
                            <label>Website</label>
                            <input type="text" class="form-control" v-model="company.website">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancel">
                        <input @click="saveCompanyDetails()" type="submit" class="btn btn-primary" value="Save">
                    </div>

                </form>
            </div>
        </div>

    </div>
</template>
<script>
    export default {
        props: {
            'base_url' : String,
        },

        mounted() {
            this.getAllCompanies();
        },

        data() {
            return {
                companies: {},
                company: {
                    id      : '',
                    name    : '',
                    logo    : '',
                    email   : '',
                    website : '',
                },
                file: '',
                errors: [],
            };
        },

        methods : {
            getAllCompanies() {
                axios
                    .get(`${this.base_url}/api/companies`)
                    .then(response => {
                        this.companies = response.data.data;
                    })
                    .catch(error => {
                        console.log(error.response);
                    });
            },

            saveCompanyDetails() {

                let formData = new FormData();
                if (this.file !== "") formData.append('file', this.file);

                formData.append('id', this.company.id);
                formData.append('name', this.company.name);
                formData.append('email', this.company.email);
                formData.append('website', this.company.website);

                axios
                    .post(`${this.base_url}/api/companies`, formData, {
                        headers: { 'Content-Type': 'multipart/form-data' }
                    })
                    .then(response => {
                        $('#companyModal').modal('hide');
                        this.getAllCompanies();
                    })
                    .catch(error => {
                        if (error.response) {
                            this.errors = error.response.data.errors;
                        }
                    });
            },

            deleteCompany(companyId) {
                axios.delete(`${this.base_url}/api/companies/${companyId}`)
                    .then(response => {
                        this.getAllCompanies();
                    })
                    .catch(error => {
                        console.log(error.response);
                    });
            },

            getCurrentCompany(companyObj) {
                this.company = companyObj;
            },

            fileHandler() {
                this.file = this.$refs.file.files[0];
            },
        }
    }
</script>
