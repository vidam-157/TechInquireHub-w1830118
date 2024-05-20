var app = app || {};

app.views.UserView = Backbone.View.extend ({
	el: ".container",

	render: function() {
		console.log("userView render")
		template = _.template($('#user_template').html());
		console.log("render view: " + app.user.attributes.username);
		this.$el.html(template(app.user.attributes));

		app.navView = new app.views.NavBarView({model: app.user});
		app.navView.render();
	},

	events:{
		'click #edit_userdetails_btn': 'editUserDetails',
		'click #edit_userpassword_btn': 'changePassword',
		'click #edit_userchangedp_btn': 'chooseProfilePic',
		'change #upload_image_input': 'uploadImage',
		'click #submitPasswordChange': 'submitPasswordChange',
	},

	submitPasswordChange: function (){
		console.log("submitPasswordChange");
		userJson = JSON.parse(localStorage.getItem("user"));
		var user_id = userJson['user_id'];

		$oldPassword = $("input#oldPassword").val();
		$newPassword = $("input#newPassword").val();
		$confirmPassword = $("input#confirmPassword").val();

		if ($newPassword != $confirmPassword) {
			alert("Passwords Doesn't Match")
		}
		else{
			var userPass = {
				'user_id': user_id,
				'oldpassword': $("input#oldPassword").val(),
				'newpassword': $("input#newPassword").val(),
				'confirmpassword': $("input#confirmPassword").val()
			};

			var url = this.model.url + "change_password";

			$.ajax({
				url: url,
				type: 'POST',
				data: userPass,
				success: (response) =>{
					console.log("response", response);
					if(response.status === true) {
						alert("Password Chnaged Successfully")
						$('#passwordModal').modal('hide');
					}
					else if (response.status === false) {
						alert("Old Password Incorrect");
					}
				},
				error: function(response){
					console.error("Error:", response);
					alert("Failed to update password. Please try again.")
				}
			})
		}

		$("input#oldPassword").val("");
		$("input#newPassword").val(""),
		$("input#confirmPassword").val("");
	},

	changePassword: function(){
		console.log("changePassword");
		$('#passwordModal').modal('show');
	},

	chooseProfilePic: function (){
		console.log("chooseProfilePic");
		$('#upload_image_input').click();
	},

	uploadImage: function (){
		userJson = JSON.parse(localStorage.getItem("user"));
		var user_id = userJson['user_id'];
		console.log("user_id", user_id);
		console.log("uploadImage");

		var validateUpdateUserProfile = validateUpdateUserProfileForm();
		validateUpdateUserProfile['user_id'] = user_id;

		console.log("validateUpdateUserProfile", validateUpdateUserProfile.userimage, validateUpdateUserProfile.user_id);

		if(validateUpdateUserProfile){
			console.log("validateUpdateUserProfile is valid", validateUpdateUserProfile);

			var formData = new FormData();
			var imageFile = $('#upload_image_input')[0].files[0];
			formData.append('image', imageFile);
			formData.append('user_id', user_id);

			console.log("formData", formData.image);

			var url = this.model.url + "edit_user_image";
			console.log("url", url);
			console.log("formData", formData);

			$.ajax({
				url: url,
				type: 'POST',
				data: formData,
				contentType: false,
				processData: false,
				success: (response) =>{
					console.log("response", response);
					validateUpdateUserProfile.userimage = response.imagePath;

					console.log("validateUpdateUserProfile", validateUpdateUserProfile.userimage);
					this.model.set(validateUpdateUserProfile);

					$updateImage = this.model.attributes.userimage;
					console.log("$updateImage", $updateImage);

					var url = this.model.url + "upload_image";
					console.log("attriibuetes", this.model.attributes);
					this.model.save(this.model.attributes,{
						"url": url,
						success: (model, response) => {
							console.log("success");
							console.log("model", model);
							console.log("response", response);

							
							userJson['userimage'] = $updateImage;
							localStorage.setItem("user", JSON.stringify(userJson));
							alert("Image Uploaded")
							window.location.reload();
							
						},
						error: (model, response) => {
							console.error("Error:", response);
							alert("Upload Failed");
						}
					});

				},
				error: function(response){
					console.error("Error:", response);
					alert("Upload Failed");
				}
			});
		}
	},

	editUserDetails: function() {
		var userJson = JSON.parse(localStorage.getItem("user"));
		console.log("userJson", userJson);
		console.log("userJson", userJson['user_id']);
		console.log("editUserDetails");

		var $editButton = this.$('#edit_userdetails_btn');

		// Toggle button text between "Edit User Details" and "Update Details"
		if ($editButton.text() === 'Edit User Details') {
			$editButton.text('Update Details');

			// Enable input fields
			this.$('input').prop('disabled', false);
			this.$('select').prop('disabled', false);
		} else {
			// Change button text back to "Edit User Details"
			$editButton.text('Edit User Details');

			// Disable input fields
			this.$('input').prop('disabled', true);
			this.$('select').prop('disabled', true);

			// Get the updated user details from the input fields
			var validateEditUserDetailsForm = validateEditUserDetailsAddForm();
			validateEditUserDetailsForm['user_id'] = userJson['user_id'];

			if (validateEditUserDetailsForm) {
				console.log("editUserDetailsForm is valid", validateEditUserDetailsForm);

				// Update model with edited details
				this.model.set(validateEditUserDetailsForm);

				var url = this.model.url + "edit_user";
				console.log("url", url);
				console.log("this.model.attributes", this.model.attributes);

				// Save the updated model to the server
				this.model.save(this.model.attributes, {
					"url": url,
					success: (model, response) => {
						localStorage.setItem("user", JSON.stringify(model));
						app.appRouter.navigate('home/user/'+userJson['user_id'], {trigger: true});
					},
					error: (model, response) => {
						console.error("Error:", response);
						alert("Failed to update user details. Please try again.")
					}
				});
			} else {
				alert("Please fill all the details");
			}
		}
	},

})
