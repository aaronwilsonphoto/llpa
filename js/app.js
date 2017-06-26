(function() {

  // Initialize Firebase
  var config = {
    apiKey: "AIzaSyAfZr1qIuEMeDEEriFeoKj-x863RIbJJgA",
    authDomain: "llpa-ae9e7.firebaseapp.com",
    databaseURL: "https://llpa-ae9e7.firebaseio.com",
    storageBucket: "llpa-ae9e7.appspot.com",
    messagingSenderId: "100503483723"
  };
  firebase.initializeApp(config);

  // Get Elements
  const txtEmail = document.getElementById('txtEmail');
  const txtPassword = document.getElementById('txtPassword');
  const btnLogin = document.getElementById('btnLogin');
  const btnLogout = document.getElementById('btnLogout');
  const passwordReset = document.getElementById('passwordReset');

  // Add login event
  btnLogin.addEventListener('click', e=> {
    // Get email and password
    const email = txtEmail.value;
    const pass = txtPassword.value;
    const auth = firebase.auth();
        if (email.length < 4) {
          alert('Please enter your Username.');
          return;
        }
        if (pass.length < 6) {
          alert('Please enter your password.');
          return;
        }
    //Sign in
    const promise = auth.signInWithEmailAndPassword(email, pass);
    promise.catch(e => alert(e.message));
    });

  // Logout Event
  btnLogout.addEventListener('click', e => {
    firebase.auth().signOut();
    alert('You have been logged out');
  });


  // Send Password Reset
  passwordReset.addEventListener('click', e => {
    const email = txtEmail.value;
    firebase.auth().sendPasswordResetEmail(email);
    if (email.length > 4){
     alert('Password Reset Sent To: ' + email); 
   } else {
     alert('Please Enter Your Username');
   }
    
  });


  // Add a realtime listener
  firebase.auth().onAuthStateChanged(firebaseUser => {
    if(firebaseUser) {
      console.log(firebaseUser);
      btnLogout.classList.remove('hide');
      btnLogin.classList.add('hide');
    } else {
      console.log('please log in');
      btnLogout.classList.add('hide');
      btnLogin.classList.remove('hide');
    }
  });

}());
