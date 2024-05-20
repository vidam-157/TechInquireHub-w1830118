var app = app || {};


app.views.LoginFormView = Backbone.View.extend({
    el: ".container",

    render: function () {
        template = _.template($('#login_template').html());
        this.$el.html(template(this.model.attributes));
		console.log("login template");
      
    },

    events: {
        "click #login_button": "logging_in",
        "click #signup_button": "signing_up",
		"click #forget-password": "forget_password",
		'click #forgetPasswordChange': 'change_forgot_password',
    },

	forget_password: function (e) {
		e.preventDefault();
		e.stopPropagation();

		console.log("Forget Password");
		$('#forgetPasswordModel').modal('show');
	},

	change_forgot_password: function () {
		
		$username = $("input#username").val();
		$newPassword = $("input#newPassword").val();
		$confirmPassword = $("input#confirmPassword").val();

		if($newPassword != $confirmPassword){
			alert("Passwords Dosen't Match")
		}
		else {
			var userPass = {
				'username': $username,
				'newpassword': $newPassword,
				'confirmpassword': $confirmPassword
			};

			var url = this.model.url + "forget_password";

			$.ajax({
				url: url,
				type: 'POST',
				data: userPass,
				success: (response) =>{
					console.log("response", response);
					if(response.status === true) {
						alert ("Password Changed Successfully")
						$('#forgetPasswordModel').modal('hide');
					}
					else if(response.status === false) {
						alert ("Username or email incorrect")
					}
				},
				error: function(response) {
					console.error("Error:", response);
					alert("Failed to Update Password")
				}
			})
		}

		$("input#username").val("");
		$("input#newPassword").val(""),
		$("input#confirmPassword").val("");
	},

    logging_in: function (e) {
		e.preventDefault();
		e.stopPropagation();

		var validateForm = validateLoginForm();
		if (!validateForm) {
			$("#errLog").html("Please Enter Credentials");
		}
		else {
			this.model.set(validateForm);
			var url = this.model.url + "login";
			console.log("url: ", url);
			this.model.save(this.model.attributes, {
				"url": url,
				success: function (model, response) {
					$("#logout").show();
					localStorage.setItem('user', JSON.stringify(model));
					console.log("Login Done");
					app.appRouter.navigate("home", {trigger: true});

				},
				error:function (model,xhr) {
					if(xhr.statsu=400){
						$("#errLog").html("Username or Password Incorrect");
					}
				}
			});
		}
    },

	signing_up: function (e) {

		e.preventDefault();
		e.stopPropagation();

		var validateForm = validateRegisterForm();

		if (!validateForm) {
			$("#errSign").html("Please fill the form");
		} 
		else {
			console.log("validateForm: ");
			this.model.set(validateForm);
			var url = this.model.url + "register";
			this.model.save(this.model.attributes, {
				"url": url,
				success: function(model, response){
					alert("Registration Successful");
					app.appRouter.navigate("#", {trigger: true, replace: true}); // Navigate to root route
				},

				error:function (model,xhr) {
					if(xhr.status === 409){
						$("#errSign").html(xhr.responseJSON.data);
						alert("Username or Email already exists")
					} else {
						$("#errSign").html();
					}
				}
			});

			console.log("details are filled");
			console.log("validateForm: " ,validateForm);
		}

		$('#regUsername').val('');
		$('#regPassword').val('');
		$('#regOccupation').val('');
		$('#regName').val('');
		$('#regEmail').val('');
	}

});
