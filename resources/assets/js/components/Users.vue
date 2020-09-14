<template>
    <div class="container">

        <div  v-if="!$gate.isAdminOrAuthor()">
            <not-found></not-found>
        </div>


        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default mt-5">
                    <div class="card-header">
                        Users
                        <button
                            class="btn btn-success float-right"
                            @click="addUser"
                        >
                            Add New<i class="fas fa-user-plus fa-fw ml-2"></i>
                        </button>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Registration Date</th>
                                    <th scope="col">Modify</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users.data">
                                    <th scope="row">{{ user.id }}</th>
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.type | toUpperCase }}</td>
                                    <td>
                                        {{ user.created_at | registrationDate }}
                                    </td>
                                    <td>
                                        <a href="#"
                                            ><i class="fa fa-edit pl-1" @click="editUser(user)"></i
                                        ></a>
                                        <a
                                            href="#"
                                            @click="onDeleteUser(user.id)"
                                            ><i class="fa fa-trash pr-1"></i
                                        ></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <pagination :data="users" @pagination-change-page="getResults">
                                <span slot="prev-nav">&lt; Previous</span>
	                            <span slot="next-nav">Next &gt;</span>
                        </pagination>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="modal fade"
            id="userModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="userModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel">
                            {{modaltitle}}
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
                        <form
                            @submit.prevent="editMode ? updateUser() : createUser()"
                            @keydown="form.onKeydown($event)"
                        >
                            <div class="form-group">
                                <input
                                    v-model="form.name"
                                    type="text"
                                    name="name"
                                    Placeholder="Enter Name"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('name')
                                    }"
                                />
                                <has-error
                                    :form="form"
                                    field="name"
                                ></has-error>
                            </div>

                            <div class="form-group">
                                <input
                                    v-model="form.email"
                                    type="text"
                                    name="name"
                                    Placeholder="Enter Email"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('email')
                                    }"
                                />
                                <has-error
                                    :form="form"
                                    field="email"
                                ></has-error>
                            </div>

                            <div class="form-group">
                                <textarea
                                    class="form-control"
                                    v-model="form.bio"
                                    rows="3"
                                    Placeholder="Short bio for user (Optional)"
                                    :class="{
                                        'is-invalid': form.errors.has('bio')
                                    }"
                                ></textarea>
                                <has-error :form="form" field="bio"></has-error>
                            </div>

                            <div class="form-group">
                                <select
                                    class="form-control"
                                    v-model="form.type"
                                    id="exampleFormControlSelect1"
                                    :class="{
                                        'is-invalid': form.errors.has('type')
                                    }"
                                >
                                    <option>Admin</option>
                                    <option>Standard User</option>
                                    <option>Author</option>
                                </select>
                                <has-error
                                    :form="form"
                                    field="type"
                                ></has-error>
                            </div>

                            <div class="form-group">
                                <input
                                    v-model="form.password"
                                    type="password"
                                    name="password"
                                    Placeholder="Enter Password"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has(
                                            'password'
                                        )
                                    }"
                                />
                                <has-error
                                    :form="form"
                                    field="password"
                                ></has-error>
                            </div>

                            <button
                                type="button"
                                class="btn btn-danger"
                                data-dismiss="modal"
                            >
                                Close
                            </button>
                            <button type="submit" class="btn btn-primary">
                                {{modaltitle}}
                            </button>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";
import EventBus from "../eventBus";

export default {
    data() {
        return {
            users: {},
            form: new Form({
                id: "",
                name: "",
                email: "",
                password: "",
                type: "",
                bio: "",
                photo: ""
            }),
            modaltitle: 'Add User',
            editMode: false
        };
    },
    mounted() {
        console.log("Component mounted.");
    },
    methods: {
        createUser() {

            this.$Progress.start();
            this.form
                .post("api/user")
                .then(() => {
                    this.loadUsers();
                    $("#userModal").modal("hide");

                    toast.fire({
                        type: "success",
                        icon: "success",
                        title: "User created successfully"
                    });

                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        loadUsers() {
            if(this.$gate.isAdminOrAuthor())
            {
                 axios.get("api/user").then(data => {
                this.users = data.data;
                });
            }
        },
        onDeleteUser(userId) {
            swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then(result => {

                if (result.value) {
                    swal.fire(
                        "Deleted!",
                        "User has been deleted.",
                        "success"
                    );

                     this.form.delete('api/user/' + userId)
                     .then(data => {
                         this.loadUsers();
                     })
                     .catch(err => {
                         swal.fire("Failed", "There was Something wrong");
                     });
                }
            });
        },
        addUser(){
            this.form.reset();
            this.modaltitle = 'Add User';
            this.editMode = false;
            $("#userModal").modal("show");
        },
        editUser(user){
                this.form.id = user.id;
                this.form.name = user.name;
                this.form.email = user.email;
                this.form.password = user.password;
                this.form.type = user.type;
                this.form.bio = user.bio;
                this.form.photo = user.photo;
                this.modaltitle = 'Edit User';
                this.editMode = true
            $("#userModal").modal("show");
        },
        updateUser()
        {
             this.$Progress.start();

             this.form.put('api/user/'+ this.form.id)
             .then((data) => {

                this.loadUsers();
                $("#userModal").modal("hide");
                swal.fire(
                        "Updated!",
                        "User Information has been updated.",
                        "success"
                    );
                this.$Progress.finish();
             })
             .catch(() => {
                  this.$Progress.fail();
             })
        },
        getResults(page = 1) {
            axios.get('api/user?page=' + page)
            .then(response => {
                this.users = response.data;
            });
        }
    },
    created() {

        EventBus.$on("my-event", ($data) => {
            console.log("my-event called on global event bus", $data);
        });
        // Fire.$on('searching', () => {

        //     let query = this.$parent.search;
        //      console.log('text', query);
        //     axios.get('/api/findUser?query=' + query)
        //     .then((data) => {
        //         this.users = data.data;
        //     })
        //     .catch();
        // })

        this.loadUsers();
    },
    filters: {
        toUpperCase(value) {
            return value.charAt(0).toUpperCase() + value.slice(1);
        },
        registrationDate(value) {
            return moment(value).format("DD MMMM YYYY");
        }
    }
};
</script>
