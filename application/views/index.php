<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TechInquire Hub</title>

    <!-- Load jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Load Underscore and Backbone -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.9.1/underscore-min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.3.3/backbone-min.js"></script>

    <!-- Load application scripts -->
    <script src="../../assets/js/app.js"></script>
    <script src="../../assets/js/routers/approuter.js"></script>
    <script src="../../assets/js/views/loginFormView.js"></script>
    <script src="../../assets/js/Models/user.js"></script>
    <script src="../../assets/js/views/homeView.js"></script>
    <script src="../../assets/js/Models/question.js"></script>
    <script src="../../assets/js/views/questionView.js"></script>
    <script src="../../assets/js/views/askQuestionView.js"></script>
    <script src="../../assets/js/views/answerQuestionView.js"></script>
    <script src="../../assets/js/Models/answer.js"></script>
    <script src="../../assets/js/views/answerView.js"></script>
    <script src="../../assets/js/views/bookmarkView.js"></script>
    <script src="../../assets/js/views/userView.js"></script>
    <script src="../../assets/js/views/navBarView.js"></script>

    <!-- Load Bootstrap and FontAwesome CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-... (hash value) ..." crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-... (hash value) ..." crossorigin="anonymous" />
    
    <!-- Load jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <!-- Load FontAwesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">

    <!-- Load Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Load jQuery Validate plugin -->
    <script src="../../assets/plugins/jquery-validate/jquery.validate.js"></script>

    <!-- Load custom CSS -->
    <link rel="stylesheet" href="../../assets/css/styles.css" />
    <link rel="stylesheet" href="../../assets/plugins/css/themes/default.css" />

    <!-- Load Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">

</head>
<body>

<!-- All components are rendered within here -->
<div class="container"></div>

<!-- First page for Login and Signup -->
<script type="text/template" id="login_template">
    <div class="login-container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card my-5">

                <!-- Navigation pills for switching between login and signup -->
                <ul class=" pill-header nav nav-pills nav-fill" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                        role="tab" aria-controls="pills-home" aria-selected="true">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile"
                            role="tab" aria-controls="pills-profile" aria-selected="false">Sign up</a>
                    </li>
                </ul>

                <!-- Tab content for login and signup forms -->
                <div class="tab-content" id="pills-tabContent">
                	<!-- Login Form -->
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="card-body">
                            <h3 class="card-title text-center">TechInquire Hub</h3>
                            <form class="form-signin">
                            	<p id="errLog" class="text-danger"></p>
                                <div class="form-label-group">
                                	<input type="text" id="inputUsername" class="form-control mb-3" placeholder="Username" required autofocus>
                                </div>
                                <div class="form-label-group">
                                    <input type="password" id="inputPassword" class="form-control mb-3" placeholder="Password" required>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <a href="#" id="forget-password" data-toggle="modal" data-target="#forgetPasswordModel">Forgot Password?</a>
                                </div>
                                <button id="login_button" class="btn btn-outline-primary btn-block mt-4" type="submit">Log in</button>
                            </form>
                        </div>
                    </div>

                    <!-- Signup Form -->
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <div class="card-body">
                            <h3 class="card-title text-center">TechInquire Hub</h3>
                            <form class="form-signin">
                                <p id="errSign" class="text-danger"></p>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <input type="text" id="regUsername" class="form-control mb-3" placeholder="Username" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" id="regName" class="form-control mb-3" placeholder="Your name" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-8">
                                        <input type="email" id="regEmail" class="form-control mb-3" placeholder="Email" required>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control mb-3" id="regOccupation">
                                            <option value="" disabled selected>Who are you</option>
                                            <option value="student">Student</option>
                                             <option value="employee">Employee</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="password" id="regPassword" class="form-control mb-3" placeholder="Password" required>
                                <button id="signup_button" class="btn btn-outline-primary btn-block mt-4" type="submit">Sign up</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Forget Password -->
    <div class="modal fade" id="forgetPasswordModel" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">Reset Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    	<span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm">
                        <div class="form-group">
                            <label for="username">Username or Email</label>
                            <input type="text" class="form-control" id="username" placeholder="Enter Username or Email" required>
                        </div>
                        <div class="form-group">
                            <label for="newPassword">New Password</label>
                            <input type="password" class="form-control" id="newPassword" placeholder="Enter new password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm new password" required>
                        </div>
                        <button type="button" class="btn btn-primary" id="forgetPasswordChange">Save changes</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</script>

<!--------------------------------------------------------***--------------------------------------------------->

<!-- Home page -->

<script type="text/template" id="home_template">

	<div id="nav-bar-container"> </div>

	<div class="container">

		<!-- Navigation bar on top of the Home page -->
		<div class="sub-nav">
			<ul class=" nav" >
				<li class="nav-item">
					<a href="#" class="nav-link" aria-current="page" style="background-color: #86C232; color:black; border: 2px solid black">
						<i class="fa-solid fa-house"></i><span class="side-title">Home</span>
					</a>
				</li>
				<li>
					<a href="#home/bookmark/<%=user_id%>" class="nav-link link-dark">
						<i class="fa-solid fa-star"></i><span class="side-title">Starred</span>
					</a>
				</li>
				<li>
					<a href="#home/user/<%=user_id%>" class="nav-link link-dark">
						<i class="fa-solid fa-user"></i><span class="side-title">Profile</span>
					</a>
				</li>
			</ul>
		</div>

		<!-- Question Display Area -->
		<div class="content-plane col-sm-10">
			<div class="question-area" id="question">
				<div class="top-questions">
					<h2>Welcome to the Questionnaire</h2>
					<button type="button" class="btn btn-primary" id="ask_question_btn">Ask a Question</button>
				</div>
				<hr>
			</div>
		</div>
	</div>
</script>

<!-------------------------------------------------------***------------------------------------------------->

<!-- User Profile Page -->

<script type="text/template" id="user_template">

	<div id="nav-bar-container"> </div>
	
	<div class="container">

		<!-- Navigation bar on top of the user profile page -->
		<div class="sub-nav">
			<ul class=" nav" >
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="fa-solid fa-house"></i><span class="side-title">Home</span>
					</a>
				</li>
				<li>
					<a href="#home/bookmark/<%=user_id%>" class="nav-link" >
					<i class="fa-solid fa-star"></i><span class="side-title">Starred</span>
					</a>
				</li>
				<li>
					<a href="#home/user/<%=user_id%>" class="nav-link" aria-current="page" style="background-color: #86C232; color:black; border: 2px solid black">
						<i class="fa-solid fa-user"></i><span class="side-title">Profile</span>
					</a>
				</li>
			</ul>
		</div>
			
		<!-- User Profile Edit/Display -->
		<div class="profile-container row">
    		<div class="col-sm-10" style="margin-left: auto;">
			<div class="user-details">
				<div class="row">
					<div class="col-sm-3">
						<div class="user-image">
							<% if (userimage != "") { %>
							<img src="<%=userimage%>" alt="User Image" class="img-fluid rounded-circle">
							<% } else { %>
							<img src="../../assets/images/userimage/face-scan.png" alt="User Image" class="img-fluid rounded-circle" height="200" width="200">
							<% } %>
						</div>
						<div class="edit-btns mt-3">
							<button type="button" class="btn btn-info" id="edit_userchangedp_btn">Edit Profile Image</button>
							<input type="file" id="upload_image_input" style="display: none;" accept="image/*">
						</div>
					</div>
					<div class="col-sm-8 offset-sm-1">
						<form class="profile-info-form">
							<div class="form-group row">
								<label for="editusername" class="col-sm-4 col-form-label font-weight-bold">User Name</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="editusername" value="<%=username%>" disabled>
								</div>
							</div>
							<div class="form-group row">
								<label for="editname" class="col-sm-4 col-form-label font-weight-bold">Display Name</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="editname" value="<%=name%>" disabled>
								</div>
							</div>
							<div class="form-group row">
								<label for="editemail" class="col-sm-4 col-form-label font-weight-bold">Email</label>
								<div class="col-sm-8">
									<input type="email" class="form-control" id="editemail" value="<%=email%>" disabled>
								</div>
							</div>
							<div class="form-group row">
								<label for="editOccupation" class="col-sm-4 col-form-label font-weight-bold">Occupation</label>
								<div class="col-sm-8">
									<select class="form-control" id="editOccupation" disabled>
										<option value="<%=occupation%>"><%=occupation%></option>
										<option value="student">Student</option>
										<option value="employee">Employee</option>
									</select>
								</div>
							</div>
							<div class="form-edit-btns mt-3">
								<button type="button" class="btn btn-primary" id="edit_userdetails_btn">Edit Details</button>
								<button type="button" class="btn btn-secondary" id="edit_userpassword_btn">Change Password</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Password Change Model -->
	<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true" >
		<div class="modal-dialog" role="document" style="margin-top: 150px">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="passwordModalLabel">Change Password</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="changePasswordForm">
						<div class="form-group">
							<label for="oldPassword">Old Password</label>
							<input type="password" class="form-control" id="oldPassword" placeholder="Enter old password" required>
						</div>
						<div class="form-group">
							<label for="newPassword">New Password</label>
							<input type="password" class="form-control" id="newPassword" placeholder="Enter new password" required>
						</div>
						<div class="form-group">
							<label for="confirmPassword">Confirm Password</label>
							<input type="password" class="form-control" id="confirmPassword" placeholder="Confirm new password" required>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="submitPasswordChange">Save changes</button>
				</div>
			</div>
		</div>
	</div>
</script>

<!-- Book Mark Page -->
<script type="text/template" id="password_template">
	<div>
		<h1>Bookmark Page</h1>
	</div>
</script>

<script type="text/template" id="question_template">
	<div class="one-question">
		<h4 class="single-question"><a href="#home/answerquestion/<%=questionid%>" style="text-decoration: none; color: navy"><%=title%></a></h4>
		<p><%= question.slice(0, 200) %>...</p>
		<div class="all-tags" style="display: flex">
			<% tags.forEach(function(tag) { %>
			<div class="tags-cover" style="margin-right: 10px">
				<p><%= tag %></p>
			</div>
			<% }); %>
			<p><strong>rate:</strong> <%= rate %></p>
		</div>
	</div>
</script>

<!-------------------------------------------------------***------------------------------------------------->

<!-- Asking Questions -->

<script type="text/template" id="add_question_template">

	<div id="nav-bar-container"> </div>

	<div class="container" >
			<!-- Navigation bar on top of the user profile page -->
		<div class="sub-nav">
			<ul class=" nav" >
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="fa-solid fa-house"></i><span class="side-title">Home</span>
					</a>
				</li>
				<li>
					<a href="#home/bookmark/<%=user_id%>" class="nav-link" >
					<i class="fa-solid fa-star"></i><span class="side-title">Starred</span>
					</a>
				</li>
				<li>
					<a href="#home/user/<%=user_id%>" class="nav-link" >
						<i class="fa-solid fa-user"></i><span class="side-title">Profile</span>
					</a>
				</li>
			</ul>
		</div>

		<!-- Page for adding the question -->
		<div class="asking-pain">
			<h2 class="question-page-title" >ASK THE PUBLIC A QUESTION</h2>
			<div class="question-title">
				<p class="q_topic">Question Title</p>
				<input type="text" class="form-control form-title" placeholder="Enter Question Title" required id="inputQuestionTitle" name="inputQuestionTitle">
			</div>

			<div class="question-title">
				<p class="q_topic">Describe your Problem</p>
				<textarea class="form-control form-title" id="inputQuestionDetails" name="inputQuestionDetails" rows="3" required></textarea>
			</div>

			<div class="question-title">
				<p class="q_topic">Upload Image</p>
				<p style="font-size: 12px">Upload an image related to your question</p>
				<input type="file" class="form-control-file" id="imageUpload" name="imageUpload">
			</div>

			<div class="question-title">
				<p class="q_topic">Add Tags</p>
				<p style="font-size: 12px">Add up to 5 tags to describe what your question is about. </p>
				<input type="text" class="form-control form-title" placeholder="e.g. (javascript, react, nodejs)" required id="inputQuestionTags" name="inputQuestionTags">
			</div>

			<div class="question-title">
				<select class="form-control" required id="questionCategory">
					<option value="" selected disabled>Category</option>
					<option value="software">Software</option>
					<option value="hardware">Hardware</option>
					<option value="programming">Programming</option>
					<option value="networking">Networking</option>
					<option value="security">Security</option>
					<option value="database">Database</option>
					<option value="web-development">Web Development</option>
					<option value="mobile-development">Mobile Development</option>
					<option value="cloud-computing">Cloud Computing</option>
					<option value="artificial-intelligence">Artificial Intelligence</option>
				</select>
			</div>

			<div class="button-container">
            	<button type="submit" id="submit_question" class="btn btn-primary submit_question">Submit Question</button>
        	</div>		
		</div>
	</div>
</script>

<!-- Answer Question -->
<script type="text/template" id="answer-question-template">

	<div id="nav-bar-container"> </div>

	<div class="container" >
		<!-- Navigation bar on top of the answer question page -->
		<div class="sub-nav">
			<ul class=" nav" >
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="fa-solid fa-house"></i><span class="side-title">Home</span>
					</a>
				</li>
				<li>
					<a href="#home/bookmark/<%=user_id%>" class="nav-link" >
						<i class="fa-solid fa-star"></i><span class="side-title">Starred</span>
					</a>
				</li>
				<li>
					<a href="#home/user/<%=user_id%>" class="nav-link" >
						<i class="fa-solid fa-user"></i><span class="side-title">Profile</span>
					</a>
				</li>
			</ul>
		</div>

		<!-- Answering area -->
		<div class="answering-pain" style="margin-left: auto;">
			<!-- Question and existing answer area -->
			<div class="q-a-container">
				<!-- The question to answered -->
				<div class="row" >
					<div class="question-display" >
						<div class="starring">
							<div class="ans-question-title">
								<h3 class="answer"><%= title %></h3>   <!--Question title -->
								<p><strong>Date Posted:</strong> <%= Qaddeddate %>    <!--Question added date -->
								<strong>Upvotes:</strong> <%= rate %> </p>  <!--Question rating -->
							</div>
							<div class="strring2">
								<!-- <p class="view-status" id="question-view-status"><%=viewstatus%></p> -->
								<% if (is_bookmark) { %>
								<i class="fa-solid fa-star fa-xl add-to-bookmark starred" id="remove-bookmark"></i>
								<% } else {%>
								<i class="fa-regular fa-star fa-xl add-to-bookmark not-starred" id="add-bookmark"></i>
								<% } %>
							</div>
							
							<div class="all-tags" >
								<% tags.forEach(function(tag) { %>
								<div class="tags-cover">
									<p><%= tag %></p>
								</div>
								<% }); %>
							</div>
						</div>

						<!-- Question body -->
						<div class="ans-question" style="margin-top: 20px">
							<p><%= question %></p>
							<% if (questionimage !== '') { %>
							<img src="<%= questionimage %>" alt="Question Image" style="max-width: 800px">
							<% } %>
						</div>
					</div>
				</div>
				<!-- Area of Existing Answers -->
				<div class="existing-answer-area" id="answer" style="display: none">
					<div class="top-answers"> </div>
					<hr>
				</div>
			</div>

			<!-- Place to add user's answer -->
			<div class="add-answer">
				<h4>Add Your Answer</h4>
				<div class="answer-title">
					<p class="topic">Your Answer</p>
					<textarea class="form-control form-title" id="inputQuestionDetails" name="inputQuestionDetails" rows="3" required></textarea>
					<hr>
					<p class="topic">Upload Image</p>
					<p style="font-size: 12px"> Upload if any image related to your answer</p>
					<input type="file" class="form-control-file" id="answerImageUpload" name="answerImageUpload">
					<hr>
					<div class="answer-btn-container">
					<button type="submit" id="submit_answer" class="btn btn-primary answer-btn">Submit Answer</button>
					</div>
				</div>

				<!-- Question Rating -->
				<div class="q_rater">
					<p> Upvote Question: </p>
					<select class="form-control" required id="questionrate">
						<option value="" selected disabled>Upvote</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
					</select>
				</div>
			</div>
		</div>
	</div>
</script>

<!-- Adding Answers -->
<script type="text/template" id="answer-template">
	<div class="answers">
		<p> <%= answer %> </p>
		<% if (answerimage !== '') { %>
		<img src="<%= answerimage %>" alt="Answer Image" style="max-width: 600px; max-height: 600px">
		<% } %>
		<p class=answer-date> - <%= answeraddeddate %> - </p>
		<hr>
	</div>
</script>


<!-- Starred Questions Page -->
<script type="text/template" id="bookmark_View">
	<div id="nav-bar-container"> </div>

	<div class="container" >
		<div class="row" style="margin-top: 100px">
			
		<!-- Navigation bar on top of the answer bookmark page -->
		<div class="sub-nav">
			<ul class=" nav" >
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="fa-solid fa-house"></i><span class="side-title">Home</span>
					</a>
				</li>
				<li>
					<a href="#home/bookmark/<%=user_id%>" class="nav-link" >
						<i class="fa-solid fa-star"></i><span class="side-title">Starred</span>
					</a>
				</li>
				<li>
					<a href="#home/user/<%=user_id%>" class="nav-link" >
						<i class="fa-solid fa-user"></i><span class="side-title">Profile</span>
					</a>
				</li>
			</ul>
		</div>

		<!-- Bookmarked questions -->
		<div class="star-questions">
			<div class="question-area" id="question">
				<div class="top-questions" style="display: flex; justify-content: space-between; align-items: center;">
					<h1>Starred Questions</h1>
					<button type="button" class="btn btn-primary" id="ask_question_btn">Ask Question</button>
				</div>
				<hr>
			</div>
		</div>
	</div>
</script>


<!-- Search bar and logout button -->
<script type="text/template" id="nav-bar-template">
	<div class="header">
		<nav class="navbar navbar-expand-lg fixed-top">
			<a class="navbar-content" href="#"><h2>TECHINQUIRE HUB</h2></a>

			<!-- The serach bar -->
			<div class="navbar-collapse justify-content-center" id="navbarToggler">
				<div class="serach-bar-container">
					<form class="form-inline">
						<input class="form-control" id="searchHome" type="search" placeholder="Search Question" aria-label="Search">
						<button class="serach-btn btn" id="homesearch" type="submit"><i class="fas fa-search"></i>search</button>
					</form>
				</div>

				<!-- Logout Button -->
				<div class="navbar-nav">
					<a href="#logout" id="logout" class="logout-btn btn btn-secondary">
						<i class="fa fa-sign-out" aria-hidden="true"></i> Log out
					</a>
				</div>
			</div>
		</nav>
	</div>
</script>

</body>
</html>