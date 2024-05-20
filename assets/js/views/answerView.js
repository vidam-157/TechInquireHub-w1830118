var app = app || {}

app.views.AnswerView = Backbone.View.extend({
	el: '#answer',

	render: function() {
		template = _.template($('#answer-template').html())
		if (this.$el.find('h1').length === 0) {
			this.$el.append('<h1>Answers</h1>');
			this.$el.css('display', 'block');
		}
		this.$el.append(template(this.model.attributes));
	}
})
