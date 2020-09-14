<template>

  <nav class="navbar fixed-top navbar-expand-lg custom-color">
  <a href="index3.html" class="brand-link navbar-brand">
            <img src="../../../../../public/img/onlinechat.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                 style="opacity: .8">
            <span class="brand-text font-weight-light">LetZ Chat</span>
    </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon">
    <i class="fas fa-bars" style="color:#fff; font-size:28px;"></i>
    </span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
      <li v-if="!auth" class="nav-item">
          <router-link  class="nav-link" to="/signup">Sign Up</router-link>
        </li>
        <li v-if="!auth" class="nav-item">
          <router-link  class="nav-link" to="/signin">Sign In</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" v-if="auth" to="/">Dashboard</router-link>
        </li>
        <li v-if="auth" class="nav-item">
          <a class="nav-link logout" @click="onLogout">Logout</a>
        </li>
    </ul>
  </div>
</nav>
</template>

<script>
export default {
    computed: {
        auth() {
            return this.$store.getters.isAuthenticated
        }
    },
    methods: {
        onLogout() {
            this.$store.dispatch('logout');
            this.$router.push('/signin')
        }
    }
}
</script>
<style scoped>
    .logout{
        text-decoration: none !important;
        cursor:pointer;
    }
    .custom-color{
        background-color: #0f4979;
    }
  .logo {
    font-weight: bold;
    color: white;
  }

  .logo a {
    text-decoration: none;
    color: white;
  }

  .navbar-toggler {
  background-color: transparent;
}

  li {
    margin: 0 16px;
  }

  li a {
    text-decoration: none !important;
    color: white !important;
  }

  li a:hover,
  li a:active,
  li a.router-link-active {
    color: #fa923f !important;
  }
</style>
