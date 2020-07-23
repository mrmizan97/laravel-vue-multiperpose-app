<template>
    <div class="container">
        <div class="row justify-content-center">
               <div class="card">
            <div class="card-header">
              <h3 class="card-title">Users Table </h3>
              <div class="card-tools">
                   <!-- Button trigger modal -->
              <button type="button" class="btn btn-success" @click="newModal">
         <i class="fas fa-user-plus"></i>
                  ADD
               </button>
            </div>
            </div>
            <!-- /.
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table  class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Type</th>
                 <th>Registared at</th>

                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user in users" :key="user.id">
                  <td>{{ user.id }}</td>
                  <td>{{ user.name  }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.type | uptext }}</td>
                  <td>{{ user.created_at | mydate }}</td>
                  <td>
                      <a href="#">
                          <i class="fas fa-edit green" @click="editModal(user)"></i>
                          </a>
                          /
                      <a href="#" @click="deleteUser(user.id)">
                          <i class="fas fa-trash-alt red"></i>
                          </a>
                  </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Type</th>
                   <th>Registared at</th>
                  <th>Action</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
    </div>
    <!-- Modal -->
<div class="modal fade" id="mymodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 v-show="!editmode" class="modal-title"  id="exampleModalLabel">Add user</h5>
        <h5 v-show="editmode" class="modal-title"  id="exampleModalLabel">Update user's info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form  @submit.prevent="editmode ? updateUser() : createUsers()">
      <div class="modal-body">

        <div class="form-group">
                  <label>Name</label>

      <input v-model="form.name" type="text" name="name" id="name"
      placeholder="Name"
        class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
      <has-error :form="form" field="name"></has-error>
    </div>
    <div class="form-group">
              <label>BoiData</label>

                        <textarea v-model="form.bio" name="bio" id="bio"
                        placeholder="Short bio for user (Optional)"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                        <has-error :form="form" field="bio"></has-error>
                    </div>
     <div class="form-group">
               <label>Email</label>

      <input v-model="form.email" type="text" name="email" id="email"
      placeholder="Email"
        class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
      <has-error :form="form" field="email"></has-error>
    </div>
        <div class="form-group">
                  <label>Password</label>

      <input v-model="form.password" type="password" name="password" id="password"
      placeholder="password"
        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
      <has-error :form="form" field="password"></has-error>
    </div>
     <div class="form-group">
               <label>User Type</label>

                        <select name="type" v-model="form.type" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                            <option value="admin">Admin</option>
                            <option value="user"> User</option>
                            <option value="author">Author</option>
                        </select>
                        <has-error :form="form" field="type"></has-error>
                    </div>
        <!-- <div class="form-group">
                 Photo

     <input  type="file" name="photo" id="photo"
        class="form-control" :class="{ 'is-invalid': form.errors.has('photo') }">
      <has-error :form="form" field="photo"></has-error>
    </div> -->
      </div>
      <div class="modal-footer">
        <button  type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
        <button v-show="editmode" type="submit" class="btn btn-primary">Update</button>
      </div>
        </form>
    </div>
  </div>
</div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                editmode:false,
                users: {},
                form: new Form({
                    id:'',
                   name: '',
                   email: '',
                   password: '',
                    type: '',
                   bio: '',
                   photo: ''

                })
            }
        },
        methods:{
            updateUser(){
              this.$Progress.start()
                this.form.put('api/user/'+this.form.id)
                .then(()=>{
                  $('#mymodal').modal('hide')
                    Swal.fire(
                  'Updated!',
                  'Info has been updated.',
                  'success'
                )

             this.$Progress.finish()
             Fire.$emit('AfterCreated')
                })
                .catch(()=>{
                this.$Progress.fail();

                })
            },
            // Edit MOdal show with data
            editModal(user){
            this.editmode=true;
            this.form.reset();
            $('#mymodal').modal('show')
            this.form.fill(user);
            },
            // New MOdal show
            newModal(){
            this.editmode=false;
            this.form.reset();
            $('#mymodal').modal('show');
            },
            // Delete
            deleteUser(id){
              Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              // Send request ot the server
               if (result.value) {
              this.form.delete('api/user/'+id)
              .then(()=>{

                Swal.fire(
                  'Deleted!',
                  'Your file has been deleted.',
                  'success'
                )
                Fire.$emit('AfterCreated')


              }).catch(()=>{
                  Swal.fire('Failed!','Somthing wrong .','warning')
              })
               }
            })
            },
            // Show
            loadUsers(){
                axios.get('api/user').then(({data})=>(this.users=data.data))
            },
            // Create
            createUsers(){
                this.$Progress.start();
               this.form.post('api/user')
                .then(()=>{
                   Fire.$emit('AfterCreated')
                 $('#mymodal').modal('hide');
                Toast.fire({
                icon: 'success',
                title: 'User created  successfully'
                })
                this.$Progress.finish()
                })
                .catch(()=>{

                });


            }
        },
        created() {
           this.loadUsers();
           Fire.$on('AfterCreated', () => {
               this.loadUsers()
           },1000)
            // setInterval(() => {
            // this.loadUsers()
            // }, 3000);
}}
</script>
