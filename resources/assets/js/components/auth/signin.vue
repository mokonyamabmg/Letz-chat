<template>
    <div class="authentication-section">
        <app-header></app-header>

        <div class="form_wrapper d-flex justify-content-center">
            <md-card class="login_form">
                <md-card-header class="text-center">
                    <div class="md-title">Login</div>
                </md-card-header>

                <md-card-content>
                    <form @submit.prevent="onLogin" class="d-flex flex-column justify-content-center">
                    <md-field :class="{ 'form-group--error': $v.email.$error }">
                        <label class="label form__label">Enter Email</label>
                        <md-input  class="form-input input" v-model.trim="$v.email.$model" required></md-input>
                    </md-field>
                    <span class="md-error" v-if="!$v.email.required && $v.email.$dirty">Email is required</span>
                    <span class="md-error" v-if="!$v.email.email && $v.email.$dirty">Invalid email</span>
                     <md-field :class="{ 'form-group--error': $v.password.$error }">
                        <label class="label form__label">Enter Password</label>
                        <md-input  class="form-input input" type="password" v-model.trim="$v.password.$model" required></md-input>
                    </md-field>
                    <span class="md-error" v-if="!$v.password.required && $v.password.$dirty">Password is required</span>
                    <span class="md-error" v-if="!$v.password.minLength && $v.password.$dirty">Password must have at least 8 {{$v.name.$params.minLength.min}} characters.</span>
                    <md-button type="submit" :disabled="$v.$invalid" class="btn-submit md-dense md-raised md-primary mt-5">Login</md-button>
                    </form>
                </md-card-content>
            </md-card>
        </div>

    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import { required, minLength, email } from 'vuelidate/lib/validators'

export default {
    data() {
        return {
                email: "",
                password: "",
                submitStatus: null
        };
    },
     validations: {
        password: {
         required,
         minLength: minLength(8)
        },
        email: {
            required,
            email
        }
  },
    methods: {
        onLogin() {

             this.$v.$touch()
            if (this.$v.$invalid) {
                this.submitStatus = 'ERROR'
            } else {
            this.submitStatus = 'PENDING'

            const formData = {
                email: this.email,
                password: this.password
            };

        this.$store.dispatch('login', {email: formData.email, password: formData.password})

            }
        }
    },
    created() {
         this.$store.dispatch('autoAuthUser');
    }
};
</script>

<style>

.login_form {
    width: 35rem;
    border: 1px solid #eee;
    padding: 40px 20px;
    box-shadow: 0 2px 3px #ccc;
    min-height: 30rem;
    margin: 20vh 20vw;
}

.form_wrapper {
    min-height: 100vh;
}

.input {
    margin: 10px auto;
  }

  .label {
    display: block;
    color: #4e4e4e;
    margin-bottom: 6px;
  }

    .form-group--error .label{
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
