var app = app || {};
app.views = {};
app.routers = {};
app.models = {};
app.collections = {};

function validateLoginForm() {
	var user = {
		'username': $("input#inputUsername").val(),
		'password': $("input#inputPassword").val()
	};
	if (!user.username || !user.password) {
		return false;
	}
	return user;
}

function validateRegisterForm() {
	var user = {
		'username': $("input#regUsername").val(),
		'password': $("input#regPassword").val(),
		'occupation': $("select#regOccupation").val(),
		'name': $("input#regName").val(),
		'email': $("input#regEmail").val(),
	};
	if (!user.username || !user.password || !user.occupation || !user.name || !user.email) {
		return false;
	}
	return user;
}

function validateUpdateUserProfileForm() {
	var userImg = {
		'userimage': $("input#upload_image_input")[0].files[0]
	};

	return userImg;
}

function validateChangePasswordForm(){
	var userPass = {
		'oldpassword': $("input#oldPassword").val(),
		'newpassword': $("input#newPassword").val(),
		'confirmpassword': $("input#confirmPassword").val()
	};

	if(userPass.newpassword !== userPass.confirmpassword){
		return false;
	}

	if (!userPass.oldpassword || !userPass.newpassword || !userPass.confirmpassword) {
		return false;
	}

	return userPass;

}

function validateAnswerForm() {
	var answer = {
		'answer': $("textarea#inputQuestionDetails").val().replace(/\n/g, '<br>'),
		'answerimage': $("input#answerImageUpload")[0].files[0],
		'rate': $("select#questionrate").val(),
		'answeraddeddate': new Date().toISOString().slice(0, 19).replace('T', ' ')
	};

	if (!answer.answer) {
		return false;
	}

	return answer;
}

function validateSearchForm(){
	var search = {
		'search': $("input#searchHome").val()
	};

	if (!search.search) {
		return false;
	}

	return search;

}

function validateEditUserDetailsAddForm(){
	// Remove disabled attribute temporarily

	var editUser = {
		'username': $("input#editusername").val(),
		'name': $("input#editname").val(),
		'email': $("input#editemail").val(),
		'occupation': $("select#editOccupation").val()
	};

	// Restore disabled attribute

	if (!editUser.username || !editUser.name || !editUser.email || !editUser.occupation) {
		return false;
	}

	return editUser;
}

function validateQuestionAddForm() {
	var question = {
		'title': $("input#inputQuestionTitle").val(),
		'question': $("textarea#inputQuestionDetails").val().replace(/\n/g, '<br>'),
		'questionimage': $("input#imageUpload")[0].files[0], // Store the image file directly
		'category': $("select#questionCategory").val(),
		'tags': $("input#inputQuestionTags").val(),
		'qaddeddate': new Date().toISOString().slice(0, 19).replace('T', ' ')
	};

	console.log("question: " + question);
	// Check if 'question' has at least 20 characters
	if (!question.question || question.question.length < 20) {
		return false;
	}

	var tagsArray = question.tags.split(',').filter(tag => tag.trim() !== ''); // Remove empty tags
	if (tagsArray.length > 5) {
		return false;
	}

	if (!question.title || !question.category) {
		return false;
	}

	return question;
}

$(document).ready(function() {
	app.appRouter = new app.routers.AppRouter();
	$(function() {
		Backbone.history.start();
	});
});
