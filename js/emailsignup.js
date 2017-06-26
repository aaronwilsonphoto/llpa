
(function() {

  // Initialize Firebase
  var config = {
    apiKey: "AIzaSyAfZr1qIuEMeDEEriFeoKj-x863RIbJJgA",
    authDomain: "llpa-ae9e7.firebaseapp.com",
    databaseURL: "https://llpa-ae9e7.firebaseio.com",
    projectId: "llpa-ae9e7",
    storageBucket: "llpa-ae9e7.appspot.com",
    messagingSenderId: "100503483723"
  };
  
  firebase.initializeApp(config);

    /**
     * Handles the sign up button press.
     */
    function handleSignUp() {
      var email = document.getElementById('email').value;
      var password = document.getElementById('password').value;
      var password2 = document.getElementById('password2').value;
      if (email.length < 4) {
        alert('Please enter an email address.');
        return;
      }
      if (password.length < 4) {
        alert('Please enter a password.');
        return;
      }
      if (password != password2) {
        alert('Please make sure passwords match');
        return;
      }

      // [START createwithemail]
      firebase.auth().createUserWithEmailAndPassword(email, password).catch(function(error) {
        // Handle Errors here.
        var errorCode = error.code;
        var errorMessage = error.message;
        // [START_EXCLUDE]
        if (errorCode == 'auth/weak-password') {
          alert('The password is too weak.');
        } else {
          alert(errorMessage);
        }
        console.log(error);
        // [END_EXCLUDE]
      });
      // [END createwithemail]
    }

    /**
     * initApp handles setting up UI event listeners and registering Firebase auth listeners:
     *  - firebase.auth().onAuthStateChanged: This listener is called when the user is signed in or
     *    out, and that is where we update the UI.
     */
    function initApp() {
      // Listening for auth state changes.
      // [START authstatelistener]
      firebase.auth().onAuthStateChanged(function(user) {
        if (user) {
          // User is signed in.
          var displayName = user.displayName;
          var email = user.email;
          var emailVerified = user.emailVerified;
          var photoURL = user.photoURL;
          var isAnonymous = user.isAnonymous;
          var uid = user.uid;
          var providerData = user.providerData;
          var location = 'home';
          alert('You can now use these credentials to log in');
          window.location = location;
        } 
      });
      // [END authstatelistener]
      document.getElementById('quickstart-sign-up').addEventListener('click', handleSignUp, false);
    }

    window.onload = function() {
      initApp();
    };

    }());
