<html>
<head>
		<title>Laravel Phone Number Authentication using Firebase</title>
		<!-- CSS only -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
	
<div class="container">
		<h1>Laravel Phone Number Authentication using Firebase - raviyatechnical</h1>
	
		<div class="alert alert-danger" id="error" style="display: none;"></div>
	
		<div class="card">
			<div class="card-header">
				Enter Phone Number
			</div>
			<div class="card-body">
	
				<div class="alert alert-success" id="sentSuccess" style="display: none;"></div>
	
				<form>
						<label>Phone Number:</label>
						<input type="text" id="number" class="form-control" placeholder="+91********">
						<div id="recaptcha-container"></div>
						<button type="button" class="btn btn-success" onclick="phoneSendAuth();">SendCode</button>
				</form>
			</div>
		</div>
			
		<div class="card" style="margin-top: 10px">
			<div class="card-header">
				Enter Verification code
			</div>
			<div class="card-body">
	
				<div class="alert alert-success" id="successRegsiter" style="display: none;"></div>
	
				<form>
						<input type="text" id="verificationCode" class="form-control" placeholder="Enter verification code">
						<button type="button" class="btn btn-success" onclick="codeverify();">Verify code</button>
	
				</form>
			</div>
		</div>
	
</div>
	
<script type="module">
	// Import the functions you need from the SDKs you need
	import { initializeApp } from "https://www.gstatic.com/firebasejs/10.5.2/firebase-app.js";
	import { getAuth, RecaptchaVerifier, signInWithPhoneNumber } from "https://www.gstatic.com/firebasejs/10.5.2/firebase-auth.js";

	const firebaseConfig = {
		apiKey: "AIzaSyATQF-X_0U-ahFrSAQvZ4AXSgOSuXYGRzk",
		authDomain: "mineral-oarlock-403808.firebaseapp.com",
		projectId: "mineral-oarlock-403808",
		storageBucket: "mineral-oarlock-403808.appspot.com",
		messagingSenderId: "156540860087",
		appId: "1:156540860087:web:2e80cc27c1c6ae3707bf32"
	};

	// Initialize Firebase
	const app = initializeApp(firebaseConfig);
	const auth = getAuth(app);

	window.onload = function () {
		render();
	};

	function render() {
		window.recaptchaVerifier = new RecaptchaVerifier(auth, 'recaptcha-container', {
			'size': 'invisible',
			'callback': (response) => {
				onSignInSubmit();
			}
		}, auth);
		recaptchaVerifier.render();
	}

	window.phoneSendAuth = function() {
		const phoneNumber = $("#number").val();
		const appVerifier = window.recaptchaVerifier;

		signInWithPhoneNumber(auth, phoneNumber, appVerifier)
		.then((confirmationResult) => {
			window.confirmationResult = confirmationResult;
			console.log(confirmationResult);

			$("#sentSuccess").text("Message Sent Successfully.");
			$("#sentSuccess").show();
		}).catch((error) => {
			$("#error").text(error.message);
			$("#error").show();
		});
	}


	window.codeverify = function() {
		const code = $("#verificationCode").val();
		confirmationResult.confirm(code).then((result) => {
			var user=result.user;
            console.log(user);
  
            $("#successRegsiter").text("you are register Successfully.");
            $("#successRegsiter").show();
		}).catch((error) => {
            $("#error").text(error.message);
            $("#error").show();
		});
	}
</script>
	
</body>
</html>