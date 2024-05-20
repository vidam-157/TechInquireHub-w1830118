var app = app || {};

app.views.bookmarkView = Backbone.View.extend({
	el: ".container",

	render: function() {
		template = _.template($("#bookmark_View").html());
		this.$el.html(template(app.user.attributes));

		app.navView = new app.views.NavBarView({model: app.user});
		app.navView.render();

		this.collection.each(function(question) {
			var questionView = new app.views.QuestionView({model: question});
			questionView.render();
		})
	}
})
