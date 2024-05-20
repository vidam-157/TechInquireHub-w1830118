var app = app || {};

app.views.AnswerQuestionView = Backbone.View.extend({
	el: '.container',

	render: function () {
		console.log('rendering answer question view');
		console.log("app.attribute: ", this.model.attributes);
		template = _.template($('#answer-question-template').html());
		this.$el.html(template(this.model.attributes));

		app.navView = new app.views.NavBarView({ model: app.user });
		app.navView.render();

		this.collection.each(function (answer) {
			var answerView = new app.views.AnswerView({ model: answer });
			answerView.render();
		});
	},

	events: {
		'click #submit_answer': 'submitAnswer',
		'click #remove-bookmark': 'removeBookmark',
		'click #add-bookmark': 'addBookmark'
	},

	addBookmark: function () {
		console.log('addBookmark');

		var currentUrl = window.location.href;
		var lastPart = currentUrl.substring(currentUrl.lastIndexOf('/') + 1);
		var $questionid = parseInt(lastPart.match(/\d+$/)[0]);

		console.log("questionid from web: " + $questionid);

		$userJson = JSON.parse(localStorage.getItem("user"));
		$userid = $userJson['user_id'];
		console.log('userid: ', $userid);

		var $bookmarkIcon = $('#add-bookmark');

		console.log('questionid: ', $questionid);
		console.log('userid: ', $userid);

		var rBookmark = {
			questionid: $questionid,
			userid: $userid
		};

		var url = this.model.url + 'add_bookmark';
		count = 0;
		console.log('count: ' + count);
		if (count == 0) {
			console.log('count 62: ' + count);
			let notificationShowing = false;

			$.ajax({
				"url": url,
				type: 'POST',
				data: rBookmark,
				success: (response) => {
					rBookmark["questionid"] = "";
					rBookmark["userid"] = "";
					console.log("questionid: " + rBookmark["questionid"])
					console.log('bookmark add');
					$bookmarkIcon.removeClass('fa-regular').addClass('fa-solid');
					$bookmarkIcon.attr('id', 'remove-bookmark');
					notificationShowing = true;
					count++;
					console.log('count 81: ' + count);
				},
				error: (xhr, status, error) => {
					console.error('Error adding bookmark:', error);
				},
			});
		}

		if ($questionid != "" && $questionid != null) { }
	},

	removeBookmark: function () {
		console.log('removeBook');

		var currentUrl = window.location.href;
		var lastPart = currentUrl.substring(currentUrl.lastIndexOf('/') + 1);
		var $questionid = parseInt(lastPart.match(/\d+$/)[0]);

		console.log("questionid from web: " + $questionid);

		$userJson = JSON.parse(localStorage.getItem("user"));
		$userid = $userJson['user_id'];

		var $bookmarkIcon = $('#remove-bookmark');

		console.log('questionid: ', $questionid);
		console.log('userid: ', $userid);

		var rBookmark = {
			questionid: $questionid,
			userid: $userid
		};

		var url = this.model.url + 'remove_bookmark';
		if ($questionid != "" && $questionid != null) {
			app.user.fetch({
				"url": url,
				type: 'POST',
				data: rBookmark,
				success: (response) => {
					rBookmark["questionid"] = "";
					rBookmark["userid"] = "";
					console.log('bookmark removed');
					$bookmarkIcon.removeClass('fa-solid').addClass('fa-regular'); // Change icon to regular
					$bookmarkIcon.attr('id', 'add-bookmark');
				},
				error: (xhr, status, error) => {
					console.error('Error removing bookmark:', error);
					alert("Error Removing Bookmark");
				}
			});
		}
	},

	submitAnswer: function (e) {
		e.preventDefault();
		e.stopPropagation();

		console.log('submitting answer');

		var validateAnswer = validateAnswerForm();

		if (validateAnswer) {
			console.log('answer is valid');
			var formData = new FormData();
			var imageFIle = $('#answerImageUpload')[0].files[0];
			formData.append('image', imageFIle);
			console.log(this.model.urlAns);

			$.ajax({
				url: this.model.urlAns + 'ans_image',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: (response) => {
					console.log('image uploaded', response);
					validateAnswer.answerimage = response.imagePath;
					this.model.set(validateAnswer);

					$questionid = this.model.attributes.questionid;
					console.log('questionid: ', $questionid);

					console.log('model asda: ', this.model.attributes);
					var url = this.model.urlAns + "add_answer";
					this.model.save(this.model.attributes, {
						"url": url,
						success: (model, response) => {
							console.log('answer submitted');

							this.collection.add(model);
							console.log('model: ', model);
							// Create and render a new view for the added answer
							var newAnswerView = new app.views.AnswerView({ model: model });
							newAnswerView.render();
						},
						error: (model, response) => {
							console.log('error in submitting answer');
						}
					})
				},
				error: (xhr, status, error) => {
					console.error('Error uploading image:', error);
				}
			});

			$('#inputQuestionDetails').val('');
			$('#answerImageUpload').val('');
			$('#questionrate').val('');
		} else {
			setTimeout(function () {
				console.log('answer is not valid');
			}, 1500);
		}
	}
});
