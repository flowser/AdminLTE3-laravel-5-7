<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Permissions Table</h3>

                <div class="card-tools">
                  <button class="btn btn-success" data-toggle="modal" data-target="#addNewPermission">Add New Permission 
                      <i class="fa fa-plus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Permission</th>  
                        <th>Modify</th>                    
                    </tr>
                   <tr v-for="permission in permissions" :key="permission.id" >
                        <td>{{permission.id}}</td>
                        <td>{{permission.name}}</td>
                        <td>
                           <a href="#"><i class="fa fa-edit blue"></i></a>
                           /
                           <a href="#"><i class="fa fa-trash red "></i></a>
                        </td>                  
                    </tr>       
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Roles Table</h3>

                <div class="card-tools">
                  <button class="btn btn-success" data-toggle="modal" data-target="#addNewRole">Add New Role 
                      <i class="fa fa-plus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Role</th>  
                        <th>Permission</th> 
                        <th>Modify</th>                    
                    </tr>
                    <tr v-for="role in roles" :key="role.id" >
                        <td>{{role.id}}</td>
                        <td>{{role.name}}</td>
                        <td>
                            <span v-for="permission in role.permissions" :key="permission.id">
                                 <div class="btn btn-warning btn-sm">{{permission.name}} </div>
                            </span>                         
                        </td>
                        <td>
                           <a href="#"><i class="fa fa-edit blue"></i></a>
                           /
                           <a href="#"><i class="fa fa-trash red "></i></a>
                        </td>                  
                    </tr>       
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div> 

        <!-- Modal permission-->
        <div class="modal fade" id="addNewPermission" tabindex="-1" role="dialog" aria-labelledby="addNewPermissionLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewPermissionLabel">Add New Permission</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="createPermission">
                        <div class="modal-body">
                            <div class="form-group">                    
                                <input name="permission" v-model="roleform.permission" id="permission" class="form-control" 
                                :class="{ 'is-invalid': roleform.errors.has('permission') }">
                                    <option value="">Select User Role</option>                    
                                    <option value="admin"> Admin</option>
                                    <option value="user"> Standard User</option>
                                    <option value="Author">Author</option>
                                >
                                <has-error :form="roleform" field="permission"></has-error>
                            </div>                     
                        </div>                    
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>      

        <!-- modal roles -->
        <div class="modal fade" id="addNewRole" tabindex="-1" role="dialog" aria-labelledby="addNewRoleLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewRoleLabel">Add New Role</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="createRole">
                        <div class="modal-body">
                            <div class="form-group">                          
                                <input v-model="roleform.name" type="text" name="name"
                                    class="form-control" placeholder="Role Name" :class="{ 'is-invalid': roleform.errors.has('name') }">
                                <has-error :form="roleform" field="name"></has-error>
                            </div>   
                            <div class="form-group"> 
                                <!-- <span v-for="permission in permissions" :key="permission.id">
                                <input type="checkbox" :id="permission.name" :value="permission.name" v-model="roleform.permission">
                                <label for="permission">{{permission.name}}</label>
                                </span>                               
                                <br> -->
                                <!-- <span>Checked Permissions: {{ Permissions }}</span>                                                                  -->
                                <select  v-model="roleform.permission" multiple>
                                 
                                         <option v-for="permission in permissions" v-bind:key="permission.id"  v-bind:value="permission.name">
                                        {{ permission.name }}
                                    </option>                                  
                                    
                                </select>
                                    <br>
                                    <span>Selected: {{ roleform.permission }}</span>
                                                        
                             </div>  
                            

                        </div>
                         <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>  
    </div> <!-- container -->
</template>
<script>
    export default {
        data(){ //is same as users
            return { //object user->name etc
             checkedPermissions: [],
                permissions: {}, //this is an object (to do with foreach at view phase)                
                permissionform: new Form({ //help in two way model bundling
                    id: '',
                    name: '',
                }),     
                roles: {},                
                 roleform: new Form({ //help in two way model bundling
                    id: '',
                    name: '',
                    permission: [],
                }), 
                
                // roleform.permission: [],
    // options: [
    //   { text: 'One', value: 'A' },
    //   { text: 'Two', value: 'B' },
    //   { text: 'Three', value: 'C' }
    // ]        
            }
        },
        methods:{
            loadPermissions(){//send http request to fetch data
                axios.get('api/permission').then(({data}) => (this.permissions = data.data)); //convert this data  and store as permission objects                
            },
            loadRoles(){
                axios.get('api/role').then(({data}) => (this.roles = data.data)); //convert this data  and store as roles objects
            },
            createPermission(){ //to send http request to our controller
                // Submit the form via a POST request
                this.permissionform.post('api/permission')
                // .then(({ data }) => { console.log(data) })
            },
             createRole(){
                this.roleform.post('api/role')
            },
        },
        created() {//same mounted
           this.loadRoles();
           this.loadPermissions();
            
        }
    }
</script>