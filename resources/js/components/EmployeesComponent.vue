<template>
    <div class="container">
        <a @click="getCurrentEmployee({
            id: '',
            firstname: '',
            lastname: '',
            email: '',
            phone: ''
        })"  href="#employeeModal" class="btn btn-primary mb-2" data-toggle="modal">Add Employee</a>

        <table class="table bg-white">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody v-for="employee in employees" v-bind:key="employee.id">
                <tr>
                    <td>
                        <strong>{{employee.firstname}} {{employee.lastname}}</strong>
                    </td>
                    <td>
                        <strong>{{ employee.email }}</strong>
                    </td>
                    <td>
                        <strong>{{ employee.phone }}</strong>
                    </td>
                    <td>    
                        <a @click="getCurrentEmployee(employee)" href="#employeeModal" class="btn btn-success" data-toggle="modal">Edit</a>
                        <button @click="deleteEmployee(employee.id)" class="btn btn-danger">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="clearfix">
            <div class="hint-text">Showing <b>{{ meta.current_page }}</b> out of <b>{{ meta.last_page }}</b> entries</div>
            <div class="text-right">
                <ul class="pagination">
                    <li class="page-item"><button @click="getAllEmployees(links.first, false)" class="page-link">First</button></li>
                    <li class="page-item"><button @click="getAllEmployees(links.prev, false)" class="page-link">Prev</button></li>
                    <li class="page-item"><button @click="getAllEmployees(links.next, false)" class="page-link">Next</button></li>
                    <li class="page-item"><button @click="getAllEmployees(links.last, false)" class="page-link">Last</button></li>
                </ul>
            </div>
        </div>

        <div id="employeeModal" class="modal fade">
            <div class="modal-dialog">
                <form class="modal-content" @submit.prevent>
                    
                    <div class="modal-header">						
                        <h4 class="modal-title">Employee</h4>                        
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">					
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" class="form-control" v-model="employee.firstname">
                            <div class="text-danger" v-if="errors.firstname">{{errors.firstname[0]}}</div>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" class="form-control" v-model="employee.lastname">
                            <div class="text-danger" v-if="errors.lastname">{{errors.lastname[0]}}</div>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control" v-model="employee.email">
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" class="form-control" v-model="employee.phone">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancel">
                        <input @click="saveEmployeeDetails()" type="submit" class="btn btn-primary" value="Save">
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
            'company_id' : String,
        },

        mounted() {
            this.getAllEmployees(`${this.base_url}/api/companies/${this.company_id}`, false);
        },

        data() {
            return {
                employees: {},
                allEmployees: [],
                employee: {
                    id         : '', 
                    firstname  : '',
                    lastname   : '',
                    company_id : '',
                    email      : '',
                    phone      : '',
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
                errors: [],
            };
        },

        methods : {
            getAllEmployees(url, refresh) {
                if (url == null) return;

                this.currentUrl = url;

                let requestPage = url.slice(-1);

                if (($.isNumeric(requestPage)) 
                    && (typeof this.allEmployees[requestPage] !== 'undefined') 
                        && !refresh){

                    this.employees = this.allEmployees[requestPage].data;
                    this.links     = this.allEmployees[requestPage].links;
                    this.meta      = this.allEmployees[requestPage].meta;

                    return;
                }

                axios
                    .get(url)
                    .then(response => {
                        let requestObj  = response.data,
                            currentPage = requestObj.meta.current_page;
 
                        this.employees = requestObj.data;
                        this.links     = requestObj.links;
                        this.meta      = requestObj.meta;

                        this.allEmployees[currentPage] = requestObj;
                    })
                    .catch(error => {
                        console.log(error.response);
                    });
            },

            saveEmployeeDetails() {

                this.employee.company_id = this.company_id;

                axios
                    .post(`${this.base_url}/api/employees`, this.employee)
                    .then(response => {
                        $('#employeeModal').modal('hide');
                        this.getAllEmployees(this.currentUrl, true);
                    })
                    .catch(error => {
                        if (error.response) {
                            this.errors = error.response.data.errors;
                        }
                    });
            },

            deleteEmployee(employeeId) {
                axios.delete(`${this.base_url}/api/employees/${employeeId}`)
                    .then(response => {
                        this.getAllEmployees(this.currentUrl, true);
                    })
                    .catch(error => {
                        console.log(error.response);
                    });
            },

            getCurrentEmployee(employeeObj) {
                this.errors = [];
                this.employee = employeeObj;
            },

        }
    }
</script>
