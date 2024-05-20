var app = app || {};

app.views.AddQuestionView = Backbone.View.extend({
	el: '.container',

	render:function () {

		template = _.template($('#add_question_template').html());
		console.log('model attributes:', this.model.attributes);
		this.$el.html(template(this.model.attributes));

		app.navView = new app.views.NavBarView({model: app.user});
		app.navView.render();

	},

	events: {
		'click #submit_question': 'submitquestion',
		"click #homesearch": "home_search"
	},
	submitquestion: function(e) {
		e.preventDefault();
		e.stopPropagation();

		var validateQuestionForm = validateQuestionAddForm();
		if (!validateQuestionForm) {
			alert("Please fill all the required feilds")
		} else {
			var formData = new FormData();
			var imageFile = $('#imageUpload')[0].files[0];
			formData.append('image', imageFile);

			$.ajax({
				url: this.model.url + 'ask_question_image',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: (response) => {
					console.log('Image uploaded successfully:', response);
					validateQuestionForm.questionimage = response.imagePath; // Assuming the server returns the image path
					this.model.set(validateQuestionForm);
					var url = this.model.urlAskQuestion + "addquestion";
					console.log("url", url);
					this.model.save(this.model.attributes, {
						"url": url,
						success: (model, response) => {
							console.log('success', model, response);
							alert("Question Added Successfully")

							$('#inputQuestionTitle').val('');
							$('#inputQuestionDetails').val('');
							$('#inputQuestionExpectation').val('');
							$('#inputQuestionTags').val('');
							$('#questionCategory').val('');
							$('#imageUpload').val('');
						},
						error: (model, response) => {
							console.log('error', model, response);
							alert("Error Adding Question, Try Again!")
						}
					});
				},
				error: (xhr, status, error) => {
					console.error('Error uploading image:', error);
					alert("Error Uploading Image");
				}
			});
		}
	},

	home_search: function(e){
		e.preventDefault();
		e.stopPropagation();

		var validateAnswer = validateSearchForm();

		var searchWord = $("#searchHome").val();

		if(searchWord != ""){
			console.log('searching')

			app.user = new app.models.User(userJson);
			console.log("user: "+ app.user);
			app.homeView = new app.views.homeView({collection: new app.collections.QuestionCollection()});

			var url = app.homeView.collection.url + "displaySearchQuestions/"+searchWord;
			console.log("url: "+ url);
			app.homeView.collection.fetch({
				reset: true,
				"url": url,
				success: function(collection, response){
					console.log("response: "+ response);
					app.homeView.render();
				},
				error: function(model, xhr, options){
					if(xhr.status == 404){
						
						app.homeView.render();
					}
					
				}
			});
		}
		else {

			app.user = new app.models.User(userJson);
			console.log("user: "+ app.user);
			app.homeView = new app.views.homeView({collection: new app.collections.QuestionCollection()});

			var url = app.homeView.collection.url + "displayAllQuestions";
			app.homeView.collection.fetch({
				reset: true,
				"url": url,
				success: function(collection, response){
					console.log("response: "+ response);
					app.homeView.render();
				},
				error: function(model, xhr, options){
					if(xhr.status == 404){
						app.homeView.render();
					}
				}
			});
		}
	}

})
