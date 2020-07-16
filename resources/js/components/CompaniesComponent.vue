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

        <div class="clearfix">
            <div class="hint-text">Showing <b>{{ meta.current_page }}</b> out of <b>{{ meta.last_page }}</b> entries</div>
            <div class="text-right">
                <ul class="pagination">
                    <li class="page-item"><button @click="getAllCompanies(links.first, false)" class="page-link">First</button></li>
                    <li class="page-item"><button @click="getAllCompanies(links.prev, false)" class="page-link">Prev</button></li>
                    <li class="page-item"><button @click="getAllCompanies(links.next, false)" class="page-link">Next</button></li>
                    <li class="page-item"><button @click="getAllCompanies(links.last, false)" class="page-link">Last</button></li>
                </ul>
            </div>
        </div>

        <div id="companyModal" class="modal fade">
            <div class="modal-dialog">
                <form class="modal-content" @submit.prevent>
                    
                    <div class="modal-header">						
                        <h4 class="modal-title">Company</h4>                        
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
            this.getAllCompanies(`${this.base_url}/api/companies`, false);
        },

        data() {
            return {
                companies: {},
                allCompanies: [],
                company: {
                    id      : '',
                    name    : '',
                    logo    : '',
                    email   : '',
                    website : '',
                },
                links: {
                    prev  : '',
                    next  : '',
                    first : '',
                    last  : '',
                },
                meta: {
                    current_page : 0,
                    last_page    : 0,
                },
                currentUrl: '',
                file: '',
                errors: [],
            };
        },

        methods : {
            getAllCompanies(url, refresh) {
                if (url == null) return;

                this.currentUrl = url;

                let requestPage = url.slice(-1);

                if (($.isNumeric(requestPage)) 
                    && (typeof this.allCompanies[requestPage] !== 'undefined') 
                        && !refresh){

                    this.companies = this.allCompanies[requestPage].data;
                    this.links     = this.allCompanies[requestPage].links;
                    this.meta      = this.allCompanies[requestPage].meta;

                    return;
                }

                axios
                    .get(url)
                    .then(response => {
                        let requestObj  = response.data,
                            currentPage = requestObj.meta.current_page;
 
                        this.companies = requestObj.data;
                        this.links     = requestObj.links;
                        this.meta      = requestObj.meta;

                        this.allCompanies[currentPage] = requestObj;

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
                        this.getAllCompanies(this.currentUrl, true);
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
                        this.getAllCompanies(this.currentUrl, true);
                    })
                    .catch(error => {
                        console.log(error.response);
                    });
            },

            getCurrentCompany(companyObj) {
                this.errors = [];
                this.company = companyObj;
            },

            fileHandler() {
                this.file = this.$refs.file.files[0];
            },
        }
    }
</script>
