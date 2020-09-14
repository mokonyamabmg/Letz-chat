<template>
    <div
        class="modal fade"
        id="postModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <form class="form-horizontal" @submit.prevent="createPost">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        Create Post
                    </h5>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="user-panel mt-1 d-flex py-0 mb-0">
                        <div class="image">
                            <img
                                src="/img/people.png"
                                class="img-circle elevation-2"
                                alt="User Image"
                            />
                        </div>
                        <div class="info">
                            <p>Bestyn Mokonyama</p>

                        </div>
                    </div>
                    <md-field :class="{ 'form-group--error': $v.title.$error}">
                        <label class="form__label">Topic</label>
                        <md-input  v-model.trim="$v.title.$model" name="title" class="form__input"></md-input>
                         <span class="md-error" v-if="!$v.title.required && $v.title.$dirty">Topic is required</span>
                        <span class="md-error" v-if="!$v.title.minLength && $v.title.$dirty">Topic must have at least 4 {{$v.title.$params.minLength.min}} letters.</span>
                    </md-field>
                    <md-field :class="{ 'form-group--error': $v.content.$error }">
                        <label class="form__label">What's on your mind?</label>
                        <md-textarea  v-model.trim="$v.content.$model" name="content"></md-textarea>
                        <span class="md-error error" v-if="!$v.content.required && $v.content.$dirty">Topic is required</span>
                        <span class="md-error error" v-if="!$v.content.minLength && $v.content.$dirty">Topic must have at least 1 {{$v.content.$params.minLength.min}} letter.</span>
                    </md-field>
                       <md-field>
                            <label class="form__label">Upload Image</label>
                            <md-file v-model="photo" name="photo" @change="updateProfile" />
                        </md-field>

                </div>
                <div class="modal-footer">
                    <md-button :disabled="$v.$invalid" class="md-raised md-primary post-btn" type="submit">Post</md-button>
                </div>
                   </form>
            </div>
        </div>
    </div>
</template>

<script>
import { required, minLength } from 'vuelidate/lib/validators'

export default {
    mounted() {
        console.log("Component mounted.");
    },
    data() {
        return {
                title: "",
                content: "",
                photo: "",
                submitStatus: null
        };
    },
    validations: {
        title: {
         required,
         minLength: minLength(4)
        },
        content: {
            required,
            minLength: minLength(1)
        }
  },
    methods: {

        createPost() {

              this.$v.$touch()
            if (this.$v.$invalid) {
                this.submitStatus = 'ERROR'
            } else {
        // do your submit logic here
            this.submitStatus = 'PENDING'


                this.$Progress.start();
            const token = this.$store.getters.getToken;
            const userId = this.$store.getters.getUserId;

                axios.post("api/posts?token="+token, {title: this.title, content: this.content, photo: this.photo, user_id: userId})
                .then(() => {

                    $("#postModal").modal("hide");

                    toast.fire({
                        type: "success",
                        icon: "success",
                        title: "Post created successfully"
                    });
                    this.$store.dispatch('fetchPosts');
                    this.$Progress.finish();
                    $v.$reset
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            }


        },
        clearFields()
        {
            this.title = '';
            this.content = '';
            this.photo = '';
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

        }
    },
    computed: {

    },
    created() {

    }
};
</script>

<style lang="scss" scoped>

    .form-group--error .form__input{
    border-bottom: 1px solid red !important;
    }

     .form-group--error .form__text{
    border: 1px solid red !important;
    }

    .form-group--error label, .form-group--error .error{
    color: red;
    }

</style>
