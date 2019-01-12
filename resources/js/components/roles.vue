<template>
    <div class="container-fluid">
        <div class="row mt-5">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Permissions Table</h3>

                <div class="card-tools">
                  <button class="btn btn-success" @click="addPermissionModal"> Add New Permission 
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
                        <td>                             
                            {{permission.id}}
                        </td>
                        <td>
                            <span  class="pl-2" >
                               <div class="btn btn-warning btn-md "> {{permission.name}} </div>
                            </span>
                        </td>
                        <td>
                           <a href="#" @click="editPermissionModal(permission)"> 
                               <!-- we take whoel parameter user to populate the whole form on
                                editing modal rather than going back to edit route of laravel -->
                               <i class="fa fa-edit blue"></i></a>
                           /
                           <a href="#" @click="deletePermission(permission.id)"><i class="fa fa-trash red "></i></a>
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
                  <button class="btn btn-success" @click="addRoleModal">Add New Role 
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
                    <tr v-for="role in roles" :key="role.id" class="pl-2">
                        <td>{{role.id}}</td>
                        <td>
                            <span  class="pl-2" >
                               <div class="btn btn-danger btn-md "> {{role.name}} </div>
                            </span>                            
                        </td>
                        <td>
                            <span v-for="permission in role.permissions" :key="permission.id" class="pl-2">
                                 <div class="btn btn-primary btn-md ">{{permission.name}} </div>
                            </span>                         
                        </td>
                        <td>
                           <a href="#" @click="editRoleModal(role, permission)" v-for="permission in role.permissions" :key="permission.id" ><i class="fa fa-edit blue"></i></a>
                           /
                           <a href="#" @click="deleteRole(role.id)"><i class="fa fa-trash red "></i></a>
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
        <div class="modal fade" id="Permission" tabindex="-1" role="dialog" aria-labelledby="PermissionLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="PermissionLabel">Add New Permission</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editPermissionmode ? updatePermission() : createPermission()">
                        <div class="modal-body">
                            <div class="form-group">                          
                                <input v-model="permissionform.name" type="text" name="name"
                                    class="form-control" placeholder="Permission Name" :class="{ 'is-invalid': permissionform.errors.has('name') }">
                                <has-error :form="permissionform" field="name"></has-error>
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
        <div class="modal fade" id="Role" tabindex="-1" role="dialog" aria-labelledby="RoleLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="RoleLabel">Add New Role</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editRolemode ? updateRole() :createRole()">
                        <div class="modal-body">
                            <div class="form-group">                          
                                <input v-model="roleform.name" type="text" name="name"
                                    class="form-control" placeholder="Role Name" :class="{ 'is-invalid': roleform.errors.has('name') }">
                                <has-error :form="roleform" field="name"></has-error>
                            </div>   
                            <div class="form-group"> 
                                <div class="btn btn-danger btn-md mb-2" > 
                                    Hold Control, then Click for Multi select Permissions for this Role
                                </div>                                                 
                                <select  v-model="roleform.permission" multiple class="form-control "
                                 :class="{ 'is-invalid': roleform.errors.has('permission') }">                                      
                                        <option v-for="permission in permissions" v-bind:key="permission.id"  v-bind:value="permission.name"  >
                                        {{ permission.name }}
                                        <!-- <p class="btn btn-outline-danger btn-sm mr-2"> fff</p> -->
                                       </option>           
                                </select>  
                                <has-error :form="roleform" field="permission"></has-error>                                  
                                    <br>
                                <p> Permission Selected:
                                    <!-- picking data from the form itslt that shows the selected permissions from array to string -->
                                    <span v-for="permission in roleform.permission" v-bind:key="permission" class="">
                                        <div class="btn btn-info btn-sm ml-2 mb-2" >{{permission}} </div>
                                    </span> 
                                </p>                                         
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
                editPermissionmode:true,
                editRolemode:true,

                permissions: {}, //this is an object (to do with foreach at view phase)                
                permissionform: new Form({ //help in two way model bundling
                    id: '',
                    name: '',
                }),     
                roles: {},                
                 roleform: new Form({ //help in two way model bundling
                    id: '',
                    name: '',
                    permission: [,],
                }),                
            }
        },
        methods:{
            updatePermission(){
                console.log('editing permission');
            },
            updateRole(){
                console.log('editing role');
            },
            addPermissionModal (){
                this.permissionform.reset();
                $('#Permission').modal('show'); 
            },
            editPermissionModal(permission){
                this.permissionform.reset();
                $('#Permission').modal('show'); 
                 this.permissionform.fill(permission);//we fill the dat from user to the form
            },
            addRoleModal (){
                this.roleform.reset();
                $('#Role').modal('show'); 
            },
            editRoleModal(role){
                this.roleform.reset();
                $('#Role').modal('show'); 
                 this.roleform.fill(role);//we fill the dat from user to the form
                
            },
            loadPermissions(){//send http request to fetch data
                axios.get('api/permission').then(({data}) => (this.permissions = data.data)); //convert this data  and store as permission objects                
            },
            loadRoles(){
                axios.get('api/role').then(({data}) => (this.roles = data.data)); //convert this data  and store as roles objects
            },            
            createPermission(){ //to send http request to our controller
                this.$Progress.start();
                // Submit the form via a POST request
                this.permissionform.post('api/permission')
                // .then(({ data }) => { console.log(data) })
                    .then(()=>{// if successfully without any errors from form submission
                        Fire.$emit('AfterCreate'); //event
                        $('#Permission').modal('hide') 
                        toast({
                            type: 'success',
                            title: 'Permission Created successfully'
                        })
                        this.$Progress.finish();  
                    })
                    .catch(()=>{
                        toast({
                            type: 'error',
                            title: 'There are errors check  your form again'
                        })
                        this.$Progress.fail();  
                    })
            },
             createRole(){
                this.$Progress.start();
                this.roleform.post('api/role')
                    .then(()=>{
                        Fire.$emit('AfterCreate'); //event
                        $('#Role').modal('hide') 
                        toast({
                            type: 'success',
                            title: 'Role linked to Permissions Created successfully'
                        })
                        this.$Progress.finish();  
                    })
                    .catch(()=>{
                        toast({
                            type: 'error',
                            title: 'There are errors check  your form again'
                            
                        })
                        this.$Progress.fail();
                    })
            },            
            deletePermission(id){
                Swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                })
                .then((result) => {
                    if (result.value) {
                    //send request to the server
                    this.permissionform.delete('api/permission/' +id)
                        .then(()=>{                        
                            Swal('Deleted!', 'The Permission has been deleted.', 'success')
                            Fire.$emit('AfterCreate'); // emite create event, with name 'AfterCreate'
                        }) 
                        .catch(()=>{
                            Swal("Failed", "There was something wrong.", "warning")
                        }) 
                    }                  
                })
            },
            deleteRole(id){
                Swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                })
                .then((result) => {
                    if (result.value) {
                    //send request to the server
                    this.roleform.delete('api/role/' +id)
                        .then(()=>{                        
                            Swal('Deleted!', 'The Role has been deleted.', 'success')
                            Fire.$emit('AfterCreate'); // emite create event, with name 'AfterCreate'
                        }) 
                        .catch(()=>{
                            swal("Failed", "There was something wrong.", "warning")
                        }) 
                    }                  
                })
            },
        },
        created() {//same mounted
        this.loadRoles();
        this.loadPermissions();

        Fire.$on('AfterCreate', ()=>{
            this.loadRoles();
           this.loadPermissions();
          })    
        }
    }
</script>