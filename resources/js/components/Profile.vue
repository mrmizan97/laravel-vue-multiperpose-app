<style scoped>
.widget-user-header{
   background-position: center center;
   background-size: cover;
   background-attachment: fixed;
}
</style>
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 mt-3">

            <!-- Widget: user widget style 1 -->
            <div class="card card-widget widget-user">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header text-white" style="background-image:url('./img/user-cover.jpg">
                <h3 class="widget-user-username text-right">{{ this.form.name }}</h3>
                <h5 class="widget-user-desc text-right">Role : {{ this.form.type }}</h5>
              </div>
              <div class="widget-user-image">
                <img class="img-circle" :src="get_image()" alt="User Avatar">
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">3,200</h5>
                      <span class="description-text">SALES</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">13,000</h5>
                      <span class="description-text">FOLLOWERS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4">
                    <div class="description-block">
                      <h5 class="description-header">35</h5>
                      <span class="description-text">PRODUCTS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->

            </div>
            </div>
            <!-- /.widget-user -->

            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>
                  <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane" id="activity">
                    <!-- Post -->
                   User activity
                  </div>


                  <div class="tab-pane" id="settings">
                    <form class="form-horizontal">
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                          <input type="name" v-model="form.name" class="form-control" id="inputName"  :class="{ 'is-invalid': form.errors.has('name') }" >
                                     <has-error :form="form" field="name"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input type="email" v-model="form.email" class="form-control" id="inputName"  :class="{ 'is-invalid': form.errors.has('email') }" >
                                     <has-error :form="form" field="email"></has-error>
                        </div>
                      </div>
                      <div class="form-group">
                                    <label for="inputExperience" class="col-sm-2 control-label">Experience</label>

                                    <div class="col-sm-12">
                                    <textarea  v-model="form.bio" class="form-control" id="inputExperience" placeholder="Experience" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                                     <has-error :form="form" field="bio"></has-error>
                                    </div>
                                </div>
                               <div class="form-group">
                                    <label for="password" class="col-sm-12 control-label">Password (leave empty if not changing)</label>

                                    <div class="col-sm-12">
                                    <input type="password"
                                        v-model="form.password"
                                        class="form-control"
                                        id="password"
                                        placeholder="Passport"
                                        :class="{ 'is-invalid': form.errors.has('password') }"
                                    >
                                     <has-error :form="form" field="password"></has-error>
                                    </div>
                                </div>
                        <div class="form-group">
                        <label for="photo" class="col-sm-2 control-label">Profile Photo</label>
                        <div class="col-sm-12">
                            <input type="file" @change="updateProfile" name="photo" class="form-input">
                            <div class="avatar img-fluid img-circle" style="margin-top:10px">
                           <img :src="get_image()" :style="styleObject"/>
                        </div>
                        </div>

                          </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button @click.prevent="updateinfo" type="submit" class="btn btn-danger">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->


        </div>
        </div>
    </div>
</template>

<script>
import { FillRenderer } from '@fullcalendar/core';
    export default {
        data() {
            return {
                 styleObject: {
                   width: '100px',
                   height: '100px'
                },
                form: new Form({
                    id:'',
                   name: '',
                   email: '',
                   password: '',
                    type: '',
                   bio: '',
                   photo: '',


                })
            }
        },
        methods: {
            updateinfo(){
                this.$Progress.start()
                this.form.put('api/profile')
                 .then(()=>{
                Swal.fire(
                  'Updated!',
                  'Profile Info has been updated.',
                  'success'
                )

             this.$Progress.finish()
             Fire.$emit('AfterCreated')
                })
                .catch(()=>{
                this.$Progress.fail();

                })
            },
            updateProfile(e){
                let file = e.target.files[0];
                let reader = new FileReader();

                if(file['size'] < 2111775)
                {
                    reader.onloadend = (file) => {
                    // console.log('RESULT', reader.result)
                     this.form.photo = reader.result;
                    }
                     reader.readAsDataURL(file);
                }else{
                    alert('File size can not be bigger than 2 MB')
                }
            },
            get_image(){
               let photo = (this.form.photo.length > 100) ? this.form.photo : "img/profile/"+ this.form.photo;
               return photo;
            },

        },
        mounted() {
            console.log('Component mounted.')
        },
        created() {
         axios.get('api/profile').then(({data})=>(this.form.fill(data)))

        }

    }
</script>
