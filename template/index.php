<?php include(ROOT_DIR. 'template/header.php'); ?>
<div class='row'>
	<div class='span8'>
		....
	</div>
	<div class='span4'>
<!-- 登录框 -->
<div class="accordion-group">
	<div class="accordion-heading">
		<h4 class="accordion-toggle" style='color:#999'> 用户登录 </h4>
	</div><!-- ./accordion-heading -->
	<div class="accordion-body in collapse" style="height: auto; ">
	<div class="accordion-inner">
<form class="form-horizontal" id='login-form' method='post' action='/portal/login'>
        <fieldset>
              <input type="text" class="input-large" id="input01" name='username' placeholder='请输入用户名'>
              <input type="text" class="input-large" id="input01" name='password' placeholder='请输入密码'>
            <div class="controls">

            <input type="submit" class="btn btn-primary btn-large" value='登录'>
            </div>
        </fieldset>
      </form>
	</div><!-- ./accordion-inner -->
  </div><!-- ./accordion-body -->
</div><!-- ./accordion-group -->

<br>
<!-- 注册框 -->
<div class="accordion-group">
	<div class="accordion-heading">
		<h4 class="accordion-toggle" style='color:#999'> 用户注册 </h4>
	</div><!-- ./accordion-heading -->
	<div class="accordion-body in collapse" style="height: auto; ">
	<div class="accordion-inner">
<form class="form-horizontal" id='register-form' method='post' action='/portal/login'>
              <input type="text" class="input-large" id="input01" name='username' placeholder='请输入用户名'>
              <input type="text" class="input-large" id="input01" name='password' placeholder='请输入密码'><br>
              <input type="text" class="input-large" id="input01" name='mspassword' placeholder='请再次输入密码'><br>
            <div class="controls">
            <input type="submit" class="btn btn-warning btn-large" value='注册'>
            </div>
        </fieldset>
      </form>
	</div><!-- ./accordion-inner -->
  </div><!-- ./accordion-body -->
</div><!-- ./accordion-group -->
<?php include(ROOT_DIR. 'template/footer.php'); ?>
