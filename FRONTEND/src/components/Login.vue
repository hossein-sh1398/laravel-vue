<template>
  <div>
        <h3>Login Form</h3>
      <form>
          <div class="form-row">
          <label>Email:</label>
          <input type="email" name="email">
        </div>
        <div class="form-row">
          <label>Password:</label>
          <input type="password" name="password">
        </div>
      </form>
  </div>
</template>

<script>
export default {
  name: 'Register'
  // data () {
  //   return {
  //   }
  // }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .form-row{
    margin-bottom: 15px;
  }
  label{
    width: 91px;
    text-align: left;
    display: inline-block;
  }
</style>
