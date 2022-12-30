<form action="/register" method="post">
  <div class="row mb-3">
    <label for="inputEmail3" class="col-sm-2 col-form-label">Фамилия</label>
    <div class="col-sm-10">
      <input type="text" name="firstname" class="form-control">
    </div>
  </div>
  <div class="row mb-3">
    <label for="inputEmail3" class="col-sm-2 col-form-label">Имя</label>
    <div class="col-sm-10">
      <input type="text" name="lastname" class="form-control">
    </div>
  </div>
  <div class="row mb-3">
    <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
    <div class="col-sm-10">
      <input type="email" name="email" class="form-control">
    </div>
  </div>
  <br />
  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
    <div class="col-sm-10">
      <input type="password" name="password" class="form-control">
    </div>
  </div>
  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-2 col-form-label">Confirm</label>
    <div class="col-sm-10">
      <input type="password" name="passwordConfirm" class="form-control">
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Регистрация</button>
</form>