<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users Table</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Add New
                        <i class="fas fa-user-plus fa-w"></i> 
                    </button>                  
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Type</th>
                         <th>Registered at</th>
                        <th>Modify</th>
                    </tr>
                    
                    <tr v-for="user in users" :key="user.id"><!--repeatition like @foreach-->
                        <td>{{user.id}}</td>
                        <td>{{user.name}}</td>
                        <td>{{user.email}}</td>
                        <td>{{user.type| upText}}</td>   
                        <td>{{user.created_at| myDate}}</td>                       
                        <td>
                            <a href="#" @click="editModal(user)"><!-- go with whole user data, not user.id -->
                                <i class="fa fa-edit blue"></i>
                            </a>
                            /
                            <a href="#" @click="deleteUser(user.id)">
                                <i class="fa fa-trash red"></i>
                            </a>
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

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update User's Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode? updateUser(): createUser()">            
                        <div class="modal-body">
                            <div class="form-group">                    
                                <input v-model="form.name" type="text" name="name"
                                placeholder="Name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">                    
                                <input v-model="form.email" type="email" name="email"
                                placeholder="Email Address"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>
                            <div class="form-group">                    
                                <textarea v-model="form.bio" id="bio" name="bio"
                                    placeholder="Short Bio for User (Optional)"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }">
                                </textarea>
                                <has-error :form="form" field="bio"></has-error>
                            </div>
                            <div class="form-group">                    
                                <select name="type" v-model="form.type" id="type" class="form-control" 
                                :class="{ 'is-invalid': form.errors.has('type') }">
                                    <option value="">Select User Role</option>                    
                                    <option value="admin"> Admin</option>
                                    <option value="user"> Standard User</option>
                                    <option value="Author">Author</option>
                                </select>
                                <has-error :form="form" field="type"></has-error>
                            </div>
                            <div class="form-group">                    
                                <input v-model="form.password" type="password" id="password" placeholder="Password"                
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" v-show="!editmode" class="btn btn-primary">Create</button>
                            <button type="submit" v-show="editmode" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data (){
            return{
                editmode:true,
                users:{},
                form: new Form({
                    id:'', // we use this o we can the id when editing sendin to database
                    name: '',
                    email: '',
                    password:'',
                    type:'',
                    bio:'',
                    photo:'',                    
                })
            }
        },
        methods: {  
             newModal(){
                this.editmode = false;
                this.form.reset();
                 $('#addNew').modal('show');   
            },
            editModal(user){
                this.editmode = true;
                this.form.reset();
                 $('#addNew').modal('show');
                 this.form.fill(user);
            },
            updateUser(){
                this.$Progress.start();
                this.form.put('api/user/' +this.form.id)
                .then(()=>{
                    //success
                    $('#addNew').modal('hide');
                     Swal('Updated!', 'Information has been Updated.', 'success')
                            Fire.$emit('AfterCreate'); // emite create event, with name 'AfterCreate'
                            this.$Progress.finish();
                })
                .catch(()=>{
                    this.$Progress.fail();
                    //errors
                     $('#addNew').modal('show');
                    Swal("Failed", "There was something wrong.", "warning")
                });
            },          
           
            deleteUser(id){
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
                    this.form.delete('api/user/' +id)
                        .then(()=>{                        
                            Swal('Deleted!', 'Your file has been deleted.', 'success')
                            Fire.$emit('AfterCreate'); // emite create event, with name 'AfterCreate'
                        }) 
                        .catch(()=>{
                            swal("Failed", "There was something wrong.", "warning")
                        }) 
                    }                  
                })
            },
            loadUsers(){
                axios.get("api/user").then(({data})=>(this.users = data.data))
            },
            createUser(){
                this.$Progress.start();
                this.form.post('api/user')
                .then(()=>{
                    //succes
                    Fire.$emit('AfterCreate'); // emite create event, with name 'AfterCreate'
                    $('#addNew').modal('hide') // <!--hide modael afterworks-->
                    toast({
                    type: 'success',
                    title: 'User Created successfully'
                    })
                    this.$Progress.finish();
                })  
                .catch(()=>{
                    //error

                })           
               
            }
        },
        created() {
            this.loadUsers();
            Fire.$on('AfterCreate', ()=> { // we listen with on, and trigger afunction
                this.loadUsers();
            })            
            //  setInterval(()=>this.loadUsers(), 3000);
        }
    }
</script>
