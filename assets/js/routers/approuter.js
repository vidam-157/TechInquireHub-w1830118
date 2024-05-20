var app = app || {};

app.routers.AppRouter = Backbone.Router.extend({

	routes: {
		"": "login",
		"home": "home",
		"home/askquestion": "askquestion",
		"home/answerquestion/:questionid": "answerquestion",
		"home/bookmark/:userid": "star",
		"home/user/:userid": "user",
		"logout": "logout"
	},

	login: function () {

		userJson = JSON.parse(localStorage.getItem("user"));

		if(userJson == null) {
			console.log("if");
			if(!app.loginView) {
				app.user = new app.models.User();
				app.loginView = new app.views.LoginFormView({ model: app.user });
				app.loginView.render();
			}
		}
		else {
			this.home();
		}
	},

	user: function (userid) {
		console.log("user route");
		console.log("userid: "+ userid);
		userJson = JSON.parse(localStorage.getItem("user"));

		if(userJson != null) {

			app.user = new app.models.User(userJson);
			app.userView = new app.views.UserView({model: new app.models.User()});

			app.userView.render();
		}
	},

	star: function(userid) {

		userJson = JSON.parse(localStorage.getItem("user"));
		$userid = userJson.user_id;
		console.log("user"+userJson);

		if (userJson != null) {
			app.user = new app.models.User(userJson);
			console.log("if");

			var url = app.user.urlAskQuestion + "bookmarkQuestions/"+$userid;
			console.log("url: "+ url);

			app.bookmarkView = new app.views.bookmarkView({collection: new app.collections.QuestionCollection()});

			app.bookmarkView.collection.fetch({
				reset: true,
				"url": url,
				success: function(collection, response){
					console.log("response: "+ response);
					app.bookmarkView.render();
				},
				error: function(model, xhr, options){
					if(xhr.status == 404){
						console.log("error 404");
						app.bookmarkView.render();
					}
					console.log("error");
				}
			});
		}
		else {
			app.appRouter.navigate("", {trigger: true});
			console.log("else")
		}
	},

	home: function() {

		userJson = JSON.parse(localStorage.getItem("user"));

		if (userJson != null) {
			app.user = new app.models.User(userJson);
			console.log("user: "+ app.user);

			app.homeView = new app.views.homeView({collection: new app.collections.QuestionCollection()});

			var url = app.homeView.collection.url + "displayAllQuestions";

			app.homeView.collection.fetch({
				reset: true,
				"url": url,
				success: function(collection, response) {
					console.log("response: "+ response);

					app.homeView.render();
				},
				error: function(model, xhr, options) {

					if(xhr.status == 404) {
						app.homeView.render();
					}
				}
			});
		} 
		else {
			app.appRouter.navigate("", {trigger: true});
			console.log("else")
		}
	},

	askquestion: function () {

		userJson = JSON.parse(localStorage.getItem("user"));

		if (userJson != null) {
			app.user = new app.models.User(userJson);
			console.log("if");

			app.askQuestionView = new app.views.AddQuestionView({model: app.user});
			app.askQuestionView.render();
		}
		else {
			app.appRouter.navigate("", {trigger: true});
			console.log("else")
		}
	},

	answerquestion:function (questionid) {

		userJson = JSON.parse(localStorage.getItem("user"));
		$user_id = userJson.user_id;

		if(userJson != null){
			app.user = new app.models.User(userJson);

			var url = app.user.urlAskQuestion + "displayAllQuestions/" + questionid;

			app.user.fetch({
				"url": url,
				success: function(model, responseQ){
					console.log("sucess");
					responseQ['username'] = app.user.get("username");
					var questionModel = new app.models.Questions(responseQ);

					var urlBookmark = app.user.urlAskQuestion + "getBookmark";
					console.log("urlBookmark: "+ urlBookmark);

					$.ajax({
						url: urlBookmark,
						type: "POST",
						data: {
							"questionid": questionid,
							"userid": $user_id
						},
						success: function(responseB) {
							
							if(responseB.is_bookmark) {
								questionModel.set("is_bookmark", true);
								console.log("true bookmarked");
								app.ansQuestionView = new app.views.AnswerQuestionView({
									model: questionModel,
									collection: new app.collections.AnswerCollection(),
									bookmark: true
								});
							}
							else {
								questionModel.set("is_bookmark", false);
								console.log("false bookmarked");
								app.ansQuestionView = new app.views.AnswerQuestionView({
									model: questionModel,
									collection: new app.collections.AnswerCollection(),
									bookmark: false
								});
							}

							var answerUrl = app.ansQuestionView.collection.url + "getAnswers/" + questionid;

							app.ansQuestionView.collection.fetch({
								reset: true,
								"url": answerUrl,
								success: function (collection, response) {
									console.log("response: " + response);
									app.ansQuestionView.render();
								},
								error: function (model, xhr, options) {
									if (xhr.status == 404) {
										console.log("error 404");
									}
									console.log("error");
								}
							});
						},
						error: function(model, xhr, options) {
							if (xhr.status == 404) {
								console.log("error 404");
							}
							console.log("error");
						}
					});
				},
				error: function(model, xhr, options){
					if(xhr.status == 404){
						console.log("error 404");
					}
					console.log("error");
				}
			})
		}
		else {
			app.appRouter.navigate("", {trigger: true});
			console.log("else")
		}
	},

	logout: function() {
		console.log("logout route");
		localStorage.clear();

		var url = app.user.url + "logout";

		$.ajax ({
			url: url,
			type: "POST",
			success: function (response) {
				window.location.href = "";
			},

			error: function(model, xhr, options) {
				if (xhr.status == 404) {
					console.log("error 404");
				}
				console.log("error");
			}
		});
	}
});
