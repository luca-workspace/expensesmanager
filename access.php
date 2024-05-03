<html style="background-image: url('./img/access.jpg'); background-repeat: no-repeat; background-size: cover;">
    <head>
      <title>Access</title>

      <meta name=viewport content="width=device-width, partial-scale=1">

      <?php
      session_start();

      //check whether the user is authenticated already
      if(isset($_SESSION["user_id"])) {
        header("Location: home.php");
        exit;
      }
      ?>

      <meta charset="UTF-8">
      <!--include Tailwind framework-->
      <script src="./JS/tailwind3.4.1.js"></script>
      <!--include daisyUI library-->
      <link href="./CSS/full.min.css" rel="stylesheet" type="text/css" />
      <!--specify default theme-->
      <html data-theme="emerald"></html>

      <style>
        /*custom font*/
        @font-face {
          font-family: "access";
          src: url("./fonts/BraahOne/BraahOne-Regular.ttf");
        }

        /*simple animation to make the slogan pulse over and over*/
        .pulsating-label {
          margin-top: 60px;
          position: absolute;
          transform: rotate(-10deg);
          color: black;
          text-shadow: -6px 5px 5px #66cc8a;
          font-family: access;
          font-size: 41px;
          animation: pulse 2s infinite;
        }

        @keyframes pulse {
          0% {
            transform: scale(1) rotate(-10deg);
          }
          50% {
            transform: scale(1.1) rotate(-10deg);
          }
          100% {
            transform: scale(1) rotate(-10deg);
          }
        }

        /*alert*/
        #alertContainer {
          position: fixed;
          bottom: -350px; /*initially hidden below the viewport */
          left: 0;
          right: 0;
          padding: 10px;
          transition: bottom 0.5s ease-in-out; /*simple transition for smooth animation */
        }
      </style>

      <script>
        function showTab(tabN) {
          //retrieve all the tabs' references
          let elements = [
            document.getElementById("logIn"),
            document.getElementById("tab1"),
            document.getElementById("signUp"),
            document.getElementById("tab2"),
          ];
          
          if(tabN === 0) {
            document.getElementById("tabToggle").classList.remove("hidden");

            tabN = 1;
          }

          //find visible tab by checking if "hidden" is included in their list of classes
          if(tabN === 1) {
            if(elements[0].classList.contains("hidden")) {
              elements[0].classList.remove("hidden");
              elements[1].classList.add("tab-active");

              elements[2].classList.add("hidden");
              elements[3].classList.remove("tab-active");
            }
          }
          else {
            if(elements[2].classList.contains("hidden")) {
              elements[2].classList.remove("hidden");
              elements[3].classList.add("tab-active");

              elements[0].classList.add("hidden");
              elements[1].classList.remove("tab-active");
            }
          }
        }
      </script>

      <!--script for AJAX, required to run PHP code that send/retrieves data to/from the database for the log in/sign up, without refreshing-->
      <script>
        //log in function
        function sendXHRRequest() {
          //this event listener is for the login; the signup one is right below in the same modality
          document.addEventListener("DOMContentLoaded", function() { //to be sent not before the page has been fully loaded
            document.getElementById("submitLogIn").addEventListener("click", function(event) {
              event.preventDefault(); //prevent form submission

              //show loader
              document.getElementById("loader").classList.remove("hidden");

              //get data from the login form
              let formData = new FormData(document.getElementById("formLogIn"));

              //start of the HTTP request section
              let xhr = new XMLHttpRequest();

              xhr.onreadystatechange = function() {
                if(xhr.readyState == XMLHttpRequest.DONE) {
                  if(xhr.status === 200) {
                    if(xhr.responseText === "0") //if 0 was returned, redirect to the homepage
                      window.location.href="./home.php";
                    else if(xhr.responseText === "-1") { //if -1 was returned, the password is not valid
                      document.getElementById("loader").classList.add("hidden"); //hide the loader
                      
                      document.getElementById("text").innerHTML = "Invalid Password!"; //change the modal text
                      document.getElementById("errorModal").showModal(); //trigger the modal
                    }
                    else if(xhr.responseText === "-2") { //if -2 was returned, the user is not registered
                      console.log("-2");
                      document.getElementById("loader").classList.add("hidden"); //hide the loader

                      document.getElementById("text").innerHTML = "User not registered. Sign up!"; //change the modal text
                      document.getElementById("errorModal").showModal(); //trigger the modal
                    }
                  }
                  else
                    console.error("Error:", xhr.statusText);
                }
              };

              //attach the data
              xhr.open("POST", "./PHP/login.php");
              xhr.send(formData);
            });
          });

          //sign up function
          document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("submitSignUp").addEventListener("click", function(event) {
              event.preventDefault(); //prevent form submission
              if(!validateForm(event)) {
                document.getElementById("loader").classList.add("hidden");
                showAlert();
                return;
              }

              document.getElementById("loader").classList.remove("hidden");

              let formData = new FormData(document.getElementById("formSignUp"));
              let xhr = new XMLHttpRequest();

              xhr.onreadystatechange = function() {
                if(xhr.readyState == XMLHttpRequest.DONE) {
                  if(xhr.responseText === "0")
                      window.location.href="./home.php";
                    else if(xhr.responseText === "-1") {
                      document.getElementById("loader").classList.add("hidden");

                      document.getElementById("text").innerHTML = "User already registered. Log in!";
                      document.getElementById("errorModal").showModal();
                    }
                    else if(xhr.responseText === "-2") {
                      document.getElementById("loader").classList.add("hidden");

                      document.getElementById("text").innerHTML = "Generic error. Try again!";
                      document.getElementById("errorModal").showModal();
                    }
                  }
              };

              xhr.open("POST", "./PHP/signup.php");
              xhr.send(formData);
            });
          });
        }

        //call the function to attach the event listener
        sendXHRRequest();
      </script>

      <script>
        function showAlert() {
          document.getElementById("alertContainer").style.bottom = "0px";
        }
        function hideAlert() {
          document.getElementById("alertContainer").style.bottom = "-350px";
        }

        //form validation (which can potentially trigger the alert)
        function validateForm(event) {
          /*Use event.target to get the element that triggered the event (in this case, the button).
            Since the button does not contain values, we need to get to the parent element, in this case, the form.
            This is accomplished with the "closest()" function.*/
          const formDataObj = new FormData(event.target.closest("form"));
          const formData = Object.fromEntries(formDataObj);

          let username = formData.username.trim();
          let password = formData.password.trim();

          if(username.length < 8 || username.length > 10 || !validatePassword(password))
            return false;
          else
            return true;
        }

        function validatePassword(password) {
          //define regular expressions (regex) with the purpose of counting specific characters
          const lowercaseRegex = /[a-z]/g; //lowercase letters
          const uppercaseRegex = /[A-Z]/g; //uppercase letters
          const digitRegex = /\d/g; //digits
          const specialCharRegex = /[!"$%&()=?^:;@#]/g; //some special characters

          //make the counts
          const lowercaseCount = (password.match(lowercaseRegex) || []).length; /*(Valid for this line and the 3 following)
                                                                                  The password is "taken" for evaluation using the
                                                                                  regular expression, which will be used by the "match"
                                                                                  function to find every occurrence of the characters
                                                                                  in the expression in the string.
                                                                                  If there are no matches, then the length of
                                                                                  the empty array [] will be returned (=0). This is necessary
                                                                                  since the match function returns a null value
                                                                                  if no matches are found, and the length of null gives error.*/
          const uppercaseCount = (password.match(uppercaseRegex) || []).length;
          const digitCount = (password.match(digitRegex) || []).length;
          const specialCharCount = (password.match(specialCharRegex) || []).length;

          //verify the requirements
          if(
            //password length between 8 and 10 characters
            password.length >= 8 &&
            password.length <= 10 &&
            //between 2 and 3 lowercase letters
            lowercaseCount >= 2 &&
            lowercaseCount <= 3 &&
            //between 2 and 3 uppercase letters
            uppercaseCount >= 2 &&
            uppercaseCount <= 3 &&
            //2 digits fixed in number
            digitCount === 2 &&
            //2 special characters fixed in number
            specialCharCount === 2

            /*the requirements are this strict to prevent people from putting unlikely passwords (too long and easily forgettable)
              and from using dates or names (passwords that are too easy)*/
          )
            return true; //valid password
          else
            return false; //invalid password
        }
      </script>
    </head>

    <body onload="showTab(0)" style="overflow-y: hidden">
      <!--LOADER-->
      <div id="loader" class="fixed top-0 left-0 w-screen h-screen flex justify-center items-center z-50 hidden" style="backdrop-filter: blur(5px);">
        <span class="loading loading-infinity loading-lg text-info" style="transform: scale(1.8);"></span>
      </div>

      <div id="tabToggle" class="flex justify-center mt-8 hidden">
        <div role="tablist" class="tabs tabs-boxed max-w-60 scale-110">
          <a id="tab1" role="tab" class="tab" onclick="showTab(1)">Log in</a>
          <a id="tab2" role="tab" class="tab" onclick="showTab(2)">Sign up</a>
        </div>
      </div>

      <div class="flex justify-center h-screen">
        <div class="flex flex-col justify-center items-center">
          <div class="flex justify-center">
            <p  class="pulsating-label" style="margin-top: 60px;position: absolute; transform: rotate(-10deg);color: black;font-size: 41px;">Learn to save!</p>
          </div>

          <!--LOG IN-->
          <div id="logIn" class="flex justify-center items-center h-screen -mt-16 hidden">
            <div class="glass p-3.5 sm:p-9 rounded-[13px] sm:rounded-[30px]" style="backdrop-filter: blur(5px);">
              <form id="formLogIn" class="flex flex-col mt-1 mb-1 space-y-4 w-1/4 min-w-80 items-center">
                <label class="input input-bordered flex items-center gap-2 w-full text-white" style="background-color: rgba(0,0,0,0.3);">
                  <input name="username" class="grow" placeholder="Username"/>
                </label>

                <label class="input input-bordered flex items-center gap-2 w-full text-white" style="background-color: rgba(0,0,0,0.3);">
                  <input type="password" name="password" class="grow" placeholder="Password"/>
                </label>

                <!--submit button-->
                <button id="submitLogIn" class="btn btn-outline btn-warning btn-wide">Go to the homepage</button>
              </form>
            </div>
          </div>

          <!--SIGN UP-->
          <div id="signUp" class="flex justify-center items-center h-screen -mt-16 hidden">
            <div class="glass p-3.5 sm:p-9 rounded-[13px] sm:rounded-[30px]" style="backdrop-filter: blur(5px);">
              <form id="formSignUp" class="flex flex-col mt-1 mb-1 space-y-4 w-1/4 min-w-80 items-center">
                <label class="input input-bordered flex items-center gap-2 w-full text-white" style="background-color: rgba(0,0,0,0.3);">
                  <input name="username" class="grow" placeholder="Username"/>
                </label>

                <label class="input input-bordered flex items-center gap-2 w-full text-white" style="background-color: rgba(0,0,0,0.3);">
                  <input type="password" name="password" class="grow" placeholder="Password"/>
                </label>

                <!--submit button-->
                <button id="submitSignUp" class="btn btn-outline btn-warning btn-wide">Go to the homepage</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!--[ERROR MODAL]-->
      <dialog id="errorModal" class="modal">
        <div class="modal-box">
          <h3 class="font-bold text-lg">Error</h3>
          <p id="text" class="py-4"></p>
          <div class="modal-action">
            <form method="dialog">
              <button class="btn">Close</button>
            </form>
          </div>
        </div>
      </dialog>

      <div id="alertContainer">
        <div role="alert" class="alert shadow-lg">
          <h3>&#10071;&#10067;</h3>
          <div>
            <h2 class="font-bold">Error in the form!</h3>
            <div class="text">The <b>username</b> must be between 8 and 10 characters long.<br/>The <b>password</b> must contain:
              <ul>
                <li>• 2-3 lowercase letters</li>
                <li>• 2-3 uppercaseletters</li>
                <li>• 2 digits</li>
                <li>• 2 special characters among: !"$%&()=?^:;@#</li>
              </ul>
            </div>
          </div>
          <button class="btn btn-sm btn-success" onclick="hideAlert()">Got it</button>
        </div>
      </div>
    </body>
</html>
