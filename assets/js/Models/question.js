var app = app || {};

app.models.Questions = Backbone.Model.extend ({

	urlRoot: '/TechInquireHub/index.php/api/Question/',

	defaults: {
		question: null,
		user_id: null,
		title: null,
		question: null,
		questionimage: null,
		category: null,
		tags: null,
		rate:null,
		answerrate:null,
		is_bookmark:null,
		viewstatus:null,
		qaddeddate:null,
		answeraddeddate:null,
	},
	
	url: '/TechInquireHub/index.php/api/Question/',
	urlAns: '/TechInquireHub/index.php/api/Answer/',

});

app.collections.QuestionCollection = Backbone.Collection.extend({
	model: app.models.Questions,
	url: '/TechInquireHub/index.php/api/Question/',
});
