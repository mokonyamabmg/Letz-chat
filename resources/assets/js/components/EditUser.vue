<template>
    <div class="container-fluid center-card">
        <div class="row justify-content-center">
            <div class="col-md-8 mt-5">
                <md-card class="mt-5">
                    <md-card-header>
                        <div class="md-title text-center">Edit Profile</div>
                    </md-card-header>

                    <md-card-content>
                            <form class="form-horizontal" @submit.prevent="updateInfo">
                                <div class="form-group" :class="{ 'form-group--error': $v.name.$error }">
                                    <label for="inputName" class="col-sm-2 control-label form__label">Name</label>

                                    <div class="col-sm-12">
                                    <input type="text" v-model.trim="$v.name.$model" class="form-control form__input" id="inputName" placeholder="Name">
                                      <div class="error" v-if="!$v.name.required && $v.name.$dirty">Name is required</div>
                                        <div class="error" v-if="!$v.name.minLength && $v.name.$dirty">Name must have at least 4 {{$v.name.$params.minLength.min}} letters.</div>
                                    </div>
                                </div>
                                <div class="form-group" :class="{ 'form-group--error': $v.email.$error }">
                                    <label for="inputEmail" class="col-sm-2 control-label form__label">Email</label>

                                    <div class="col-sm-12">
                                    <input type="email" v-model.trim="$v.email.$model" name="email" class="form-control form__input" id="inputEmail" placeholder="Email">
                                    <div class="error" v-if="!$v.email.required && $v.email.$dirty">Email is required</div>
                                    <div class="error" v-if="!$v.email.email  && $v.email.$dirty">Email is invalid</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="photo" class="col-sm-2 control-label">Profile Photo</label>
                                    <div class="col-sm-12">
                                        <input type="file" @change="updateProfile" name="photo" class="form-input">
                                    </div>

                                </div>

                                <div class="form-group" :class="{ 'form-group--error': $v.email.$error }">
                                    <label for="password" class="col-sm-12 control-label form__label">Password (leave empty if not changing)</label>

                                    <div class="col-sm-12">
                                    <input type="password"
                                        v-model="password"
                                        class="form-control form__input"
                                        id="password"
                                        placeholder="Passport"
                                    >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-12">
                                    <button :disabled="$v.$invalid" type="submit" class="btn btn-success">Update</button>
                                    </div>
                                </div>
                                </form>
                    </md-card-content>
                </md-card>
            </div>
        </div>
    </div>
</template>
<script>

import { required, minLength, between, email } from 'vuelidate/lib/validators'

export default {
    data() {
        return {
            id: "",
                name: "",
                email: "",
                password: "",
                photo: "",
                submitStatus: null
        };
    },
    validations: {
        name: {
         required,
         minLength: minLength(4)
        },
        email: {
            required,
            email
        }
  },
    methods: {
        updateProfile(e) {
            let file = e.target.files[0];
            let reader = new FileReader();

            if (file["size"] < 2097152) {
                reader.onloadend = () => {
                    this.photo = reader.result;
                    // console.log(this.form);
                };
                reader.readAsDataURL(file);
            } else {
                swal.fire({
                    type: "error",
                    title: "Ooops...",
                    text: "You are uploading a large file"
                });
            }
        },
        updateInfo() {

              this.$v.$touch()
            if (this.$v.$invalid) {
                this.submitStatus = 'ERROR'
            } else {
        // do your submit logic here
            this.submitStatus = 'PENDING'

            axios.put('api/user/' + this.id, {name: this.name, email: this.email, password: this.password, photo: this.photo})
            .then(res => {
                 this.$store.dispatch('fetchAthenticatedUser');
                this.$router.push("/");

                swal.fire(
                        "Updated!",
                        "User Information has been updated.",
                        "success"
                    );
            })
            .catch(err => {
                console.log(res);
            })

      }


            // this.$store.dispatch('updateUser', {user_id: this.id, name: this.user.name, email: this.user.email, photo: this.user.photo, password: this.user.password});
        },
        getProfilePhoto() {
            let photo =
                this.form.photo.length > 200
                    ? this.form.photo
                    : "/img/profile/" + this.form.photo;
            return photo;
        }
    },
    mounted() {
    },
    created() {
        // this.$store.dispatch('fetchAthenticatedUser', {id: this.$store.getters.getUserId});
        this.id = this.$store.getters.getUserId;

         axios.get('api/authUser/' + this.id)
            .then(res => {

                this.name = res.data.user.name;
                this.email = res.data.user.email;
                this.password = "";
            })
            .catch(err => {
                console.log(err);
            })
    }
};
</script>

<style lang="scss" scoped>
    md-card{
        margin-top: 20rem !important;
    }

    .form-group--error .form__input{
    border: 1px solid red !important;
    background-color: #ffc9aa !important;
    }

    .form-group--error label, .form-group--error .error{
    color: red;
    }

</style>
