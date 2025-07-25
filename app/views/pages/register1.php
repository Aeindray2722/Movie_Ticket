<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Movie Booking Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/>
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css"/>
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/login.css" />

</head>

<body>
  <div class="main-dashboard-wrapper">
    <nav class="public-navbar">
      <div class="navbar-left">
        <h3 class="logo-text">Minglarpr</h3>
      </div>
      <div class="navbar-right">
        <ul class="nav-links">
          <li><a href="<?php echo URLROOT; ?>/pages/register">Register</a></li>
          <li><a href="<?php echo URLROOT; ?>/pages/login">Login</a></li>
          <li><a href="<?php echo URLROOT; ?>/pages/home">Guest</a></li>
        </ul>
      </div>
    </nav>

    <div class="dashboard-content-area">
      <div class="background-overlay"></div>

      <div class="register-box">
        <div class="image-side"></div>
        <div class="form-side">
          <h2 class="text-center mb-4">Register</h2>

          <form class="login100-form validate-form" name="contactForm" method="POST"
            action="<?php echo URLROOT; ?>/auth/register">

            <?php require APPROOT . '/views/components/auth_message.php'; ?>
            <div class="wrap-input100 validate-input" data-validate="Valid Name is required:">
              <input class="input100" type="text" id="name" onfocus="this.value=''" name="name" placeholder="Username">
              <span class="focus-input100"></span>
              <span class="symbol-input100">
                <i class="fa fa-user" aria-hidden="true"></i>
              </span>
            </div>
            <p class="text-danger ml-4">
              <?php
              if (isset($data['name-err']))
                echo $data['name-err'];
              ?>
            </p>

            <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
              <input class="input100" type="email" id="email" name="email" placeholder="Email">
              <span class="focus-input100"></span>
              <span class="symbol-input100">
                <i class="fa fa-envelope" aria-hidden="true"></i>
              </span>
            </div>
            <p class="text-danger ml-4">
              <?php
              if (isset($data['email-err']))
                echo $data['email-err'];
              ?>
            </p>

           <div class="wrap-input100 validate-input" data-validate="Valid phone is required: ex@abc.xyz">
              <input class="input100" type="phone" id="phone" name="phone" placeholder="phone">
              <span class="focus-input100"></span>
              <span class="symbol-input100">
                <i class="fa fa-phone" aria-hidden="true"></i>
                
              </span>
            </div>
            <p class="text-danger ml-4">
              <?php
              if (isset($data['phone-err']))
                echo $data['phone-err'];
              ?>
            </p>

            <div class="wrap-input100 validate-input" data-validate="Password is required">
              <input class="input100" type="password" name="password" placeholder="Password" id="myInput">
              <span class="focus-input100"></span>
              <span class="symbol-input100">
                <i class="fa fa-lock" aria-hidden="true"></i>
              </span>
            </div>
            <input type="checkbox" onclick="myFunction()">Show Password
            <p class="text-danger ml-4">
              <?php
              if (isset($data['password-err']))
                echo $data['password-err'];
              ?>
            </p>


            <div class=" offset-3">
              <button type="submit" class="btn btn-register w-50 justify-content-center"
                name="btn_register">Register</button>
            </div>

            <div class="text-center mt-2">
              Already have an account? <a href="<?php echo URLROOT; ?>/pages/login">Login</a>
            </div>
          </form>
          <!-- <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" required>
          <label class="form-check-label">I agree with the terms and conditions</label>
        </div> -->
        </div>
      </div>
    </div>
  </div>
<script>
	// Show Password
	function myFunction() {
		var x = document.getElementById("myInput");
		if (x.type === "password") {
			x.type = "text";
		} else {
			x.type = "password";
		}
	}

    $(function () {

		var str=$('name').val();
		if(/^[a-zA-Z- ]*$/.test(str) == false) {
				alert('Your search string contains illegal characters.');
			}
        $("form[name='contactForm']").validate({
            // Define validation rules
            rules: {
                name: "required",
                email: "required",
                password: "required",
                
                name: {
                    required: true,// to show configuration error message
					minlength: 6,// limit input value, 	Input value must have greater than or equal to minLength character length
                    maxlength: 20,//limit input value, 	Input value must have less than or equal to maxLength character length

                },
                email: {
                    required: true,
					minlength: 6,
                    maxlength: 50,
                    email: true
                },
                password: {
					minlength: 8,
					maxlength: 30,
					required: true,
					//pwcheck: true,
					// checklower: true,
					// checkupper: true,
					// checkdigit: true
                },
                
            },
            // Specify validation error messages
			//  config error message
            messages: {
				name: {
				required: "Please enter your name",
				minlength: "Name must be min 6 characters long",

				},
                email: {
                    required: "Please enter your email",
                    minlength: "Please enter a valid email address",
                },
                password: {
                    required: "Please enter your password",
                    minlength: "Password length must be min 8 characters long",
                    maxlength: "Password length must not be more than 30 characters long"
                },
                
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    });

	// Email Validation
	$(document).ready(function() {

		// form autocomplete off
		// call input tag, set attribute [attr(attribute,value)]	
		$(":input").attr('autocomplete', 'off');

		// remove box shadow from all text input
		// call input tag
		$(":input").css('box-shadow', 'none');
	// remove focus from the text field, remove cursor
		$("#name").blur(function() {
		
		var name  		= 		$('#name').val();//to get the values of form elements(input field)
		
		
		// if name is empty then return
		if(name == "") {
			return;
		}
		// call formRegister method from controllers>auth>formRegister()
		var form_url = '<?php echo URLROOT; ?>/auth/formRegister';

		// send ajax request if name is not empty
		$.ajax({
				url:form_url,
				type: 'post',
				data: {
					'name':name,

			},
		
			success:function(response) {	
			
				// clear span before error message
				$("#name_error").remove();
			
				// adding span after name textbox with error message
				$("#name").after("<span id='name_error' class='text-danger'>"+response+"</span>");
			},
		
			error:function(e) {
				$("#result").html("Something went wrong");
			}
		
		});
	});


		// ------------------- [ Email blur function ] -----------------

		$("#email").blur(function() {
		
			var email = $('#email').val();
			// if email is empty then return
			if(email == "") {
				return;
			}
			var form_url = '<?php echo URLROOT; ?>/auth/formRegister';
		
			// send ajax request if email is not empty
			$.ajax({
					url:form_url,
					type: 'post',
					data: {
						'email':email,
						'email_check':1,
				},
			
				success:function(response) {	
				
					// clear span before error message
					$("#email_error").remove();
				
					// adding span after email textbox with error message
					$("#email").after("<span id='email_error' class='text-danger'>"+response+"</span>");
				},
			
				error:function(e) {
					$("#result").html("Something went wrong");
				}
			
			});
		});
		$("#passsword").blur(function() {
		
		var password = $('#password').val();
		
		
		// if password is empty then return
		if(password == "") {
			return;
		}
		var form_url = '<?php echo URLROOT; ?>/auth/formRegister';

		// send ajax request if password is not empty
		$.ajax({
				url:form_url,
				type: 'post',
				data: {
					'password':password,

			},
		
			success:function(response) {	
			
				// clear span before error message
				$("#password_error").remove();
			
				// adding span after password textbox with error message
				$("#password").after("<span id='password_error' class='text-danger'>"+response+"</span>");
			},
		
			error:function(e) {
				$("#result").html("Something went wrong");
			}
		
		});
	});
	// -----------[ Clear span after clicking on inputs] -----------

	$("#name").keyup(function() {
		$("#error").remove();
	});
	$("#email").keyup(function() {
		$("#error").remove();
		$("span").remove();
	});

	$("#password").keyup(function() {
		$("#error").remove();
	});

	$("#c_password").keyup(function() {
		$("#error").remove();
	});
	});
</script>
</body>

</html>