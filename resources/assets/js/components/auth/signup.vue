<template>
<div>
<app-header></app-header>
  <div class="form_wrapper">
            <md-card class="login_form">
                <md-card-header class="text-center">
                    <div class="md-title">Register</div>
                </md-card-header>

                <md-card-content>
                <form @submit.prevent="onSubmit" class="d-flex flex-column justify-content-center">
                    <md-field :class="{ 'form-group--error': $v.name.$error }">
                        <label class="label form__label">Enter Name</label>
                        <md-input v-model.trim="$v.name.$model" class="input"></md-input>
                    </md-field>
                    <div></div>
                     <span class="md-error error" v-if="!$v.name.required && $v.name.$dirty">Name is required</span>
                    <md-field :class="{ 'form-group--error': $v.email.$error }">
                        <label class="label form__label">Enter Email</label>
                        <md-input
                        v-on:input="debounceUsername"
                        class="input" autofocus="">
                        </md-input>
                    </md-field>
                     <span class="md-error error" v-if="!$v.email.required && $v.email.$dirty">Email is required</span>
                    <span class="md-error" v-if="!$v.email.email && $v.email.$dirty">Invalid email</span>
                    <span class="md-error" v-if="!$v.email.unique">Email already exist</span>
                     <md-field :class="{ 'form-group--error': $v.password.$error }">
                        <label class="label form__label">Enter Password</label>
                        <md-input type="password" v-model.trim="$v.password.$model" class="input"></md-input>
                    </md-field>
                      <span class="md-error error" v-if="!$v.password.required && $v.password.$dirty">Password is required</span>
                    <span class="md-error" v-if="!$v.password.minLength && $v.password.$dirty">Password must have at least 8 {{$v.password.$params.minLength.min}} characters.</span>
                     <md-field :class="{ 'form-group--error': $v.confirmPassword.$error }">
                        <label class="label form__label">Confirm Password</label>
                        <md-input type="password" v-model.trim="$v.confirmPassword.$model" class="input"></md-input>
                    </md-field>
                    <span class="md-error error" v-if="!$v.confirmPassword.sameAsPassword && $v.confirmPassword.$dirty">Confirmation password must match</span>
                        <md-field>
                            <label>Upload Profile Image</label>
                            <md-file v-model="photo" name="photo" @change="updateProfile" />
                        </md-field>

                        <div class="d-flex justify-content-center" :class="{ 'form-group--error': $v.terms.$error }">
                        <md-checkbox v-model.trim="$v.terms.$model"><span class="form__label">Do you agree on Terms</span></md-checkbox>
                        </div>

                    <md-button type="submit" :disabled="$v.$invalid" class="btn-submit md-dense md-raised md-primary">Register</md-button>
                </form>
                </md-card-content>
            </md-card>
        </div>
  </div>
</template>

<script>
import { required, minLength, between, email, sameAs } from 'vuelidate/lib/validators'
import _ from 'lodash'

 export default {
    data () {
      return {
        email: '',
        name: '',
        password: '',
        confirmPassword: '',
        photo: null,
        terms: false,
        submitStatus: null
      }
    },
     validations: {
        confirmPassword: {
            sameAsPassword: sameAs('password')
        },
        password: {
         required,
         minLength: minLength(8)
        },
        email: {
            required,
            email,
            unique: val => {
                if(val === '') return true
                  return axios.get('api/checkEmail/' + val)
                .then(res => {
                    return Object.keys(res.data).length === 0
                })

            }
        },
        name: {
            required
        },
        terms: {
            required
        }
  },
    methods: {
      onSubmit () {
         this.$v.$touch()
            if (this.$v.$invalid) {
                this.submitStatus = 'ERROR'
            } else {
        // do your submit logic here
            this.submitStatus = 'PENDING'

        const formData = {
          email: this.email,
          name: this.name,
          password: this.password,
          confirmPassword: this.confirmPassword,
          photo: this.photo,
          terms: this.terms
        }


       this.$store.dispatch('signUp', {
            email: formData.email,
          name: formData.name,
          password: formData.password,
          confirmPassword: formData.confirmPassword,
          terms: formData.terms,
          photo: formData.photo
       });

        }

      },
              updateProfile(e)
        {
            let file = e.target.files[0];

            let reader = new FileReader();

            if(file['size'] < 2111775)
            {

                 reader.onloadend = () => {
                this.photo = reader.result;
                }

                 reader.readAsDataURL(file);
            }else{

                swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'You are uploading a large file'
                });
            }

        },
        debounceUsername:
        _.debounce(function (e) {
          this.email = e;
        }, 800, {leading: false, trailing: true})
    },
    created() {
         this.$store.dispatch('autoAuthUser');
    }
  }
</script>

<style>
.login_form {
    width: 35rem;
    border: 1px solid #eee;
    box-shadow: 0 2px 3px #ccc;
    margin-top: 10rem;
}

.form_wrapper {
    display: flex;
    justify-content: center;
}

.input {
    margin: 10px auto;
  }

  .label {
    display: block;
    color: #4e4e4e;
    margin-bottom: 6px;
  }

.inline{
    display: inline;
}

 .form-group--error .label, .form-group--error span{
    color: red !important;
    }

    .form-group--error label, .form-group--error .error, .md-error{
    color: red;
    }

.btn-submit{
    background-color: #0f4979 !important;
    color: #fff !important;
}
</style>
