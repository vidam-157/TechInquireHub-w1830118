var app = app || {};
app.models.User = Backbone.Model.extend ({

	urlRoot: '/TechInquireHub/index.php/api/User/',

	defaults: {
		name: "",
		email: "",
		username: "",
		password: "",
		user_id: null,
		occupation: "",
		premium: false,
		userimage: "",
	},
	
	url: '/TechInquireHub/index.php/api/User/',
	urlAskQuestion: '/TechInquireHub/index.php/api/Question/',
	urlAnswerQuestion: '/TechInquireHub/index.php/api/Answer/'

});
