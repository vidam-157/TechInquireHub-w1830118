var app = app || {};

app.views.SearchBarView = Backbone.View.extend({
    el: '#searchbar-container',

    render: function() {
        template = _.template($('#search-bar-template').html());
        this.$el.html(template());
        console.log('rendering search bar');
    }
});
