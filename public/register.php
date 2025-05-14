

<?php
// Include the session start and middleware
session_start();
require_once __DIR__ . '/../middleware/Authenticate.php';

// If you want to check if the user is authenticated
Authenticate::checkAuth(); // This will redirect to login if not authenticated

// Login logic below...
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Spotify Registration</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #0f0f0f;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: Arial, sans-serif;
    }

    .visually-hidden {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0, 0, 0, 0);
      white-space: nowrap;
      border: 0;
    }

    #form {
      display: grid;
      place-items: center;
      width: 300px;
      height: 520px;
      padding: 25px;
      background-color: #161616;
      box-shadow: 0px 15px 60px #00FF7F;
      outline: 1px solid #2b9962;
      border-radius: 10px;
      position: relative;
    }

    #form-body {
      position: absolute;
      top: 50%;
      right: 25px;
      left: 25px;
      width: 230px;
      margin: -180px auto 0 auto;
    }

    #welcome-lines {
      text-align: center;
      line-height: 1;
    }

    #welcome-line-1 {
      color: #00FF7F;
      font-weight: 600;
      font-size: 40px;
    }

    #welcome-line-2 {
      color: #ffffff;
      font-size: 18px;
      margin-top: 17px;
    }

    #input-area {
      margin-top: 40px;
    }

    .form-inp {
      padding: 11px 25px;
      background: transparent;
      border: 1px solid #e3e3e3;
      line-height: 1;
      border-radius: 8px;
    }

    .form-inp:focus-within {
      border: 1px solid #00FF7F;
    }

    .form-inp:not(:last-child) {
      margin-bottom: 15px;
    }

    .form-inp input {
      width: 100%;
      background: none;
      font-size: 13.4px;
      color: #00FF7F;
      border: none;
      padding: 0;
      margin: 0;
    }

    .form-inp input:focus {
      outline: none;
    }

    #submit-button-cvr {
      margin-top: 20px;
    }

    #submit-button {
      display: block;
      width: 100%;
      color: #00FF7F;
      background-color: transparent;
      font-weight: 600;
      font-size: 14px;
      margin: 0;
      padding: 14px 13px 12px 13px;
      border: 0;
      outline: 1px solid #00FF7F;
      border-radius: 8px;
      line-height: 1;
      cursor: pointer;
      transition: all ease-in-out 0.3s;
    }

    #submit-button:hover {
      background-color: #00FF7F;
      color: #161616;
    }

    #forgot-pass {
      text-align: center;
      margin-top: 10px;
    }

    #forgot-pass a {
      color: #868686;
      font-size: 12px;
      text-decoration: none;
    }

    #bar {
      position: absolute;
      left: 50%;
      bottom: -50px;
      width: 28px;
      height: 8px;
      margin-left: -33px;
      background-color: #00FF7F;
      border-radius: 10px;
    }

    #bar:before,
    #bar:after {
      content: "";
      position: absolute;
      width: 8px;
      height: 8px;
      background-color: #ececec;
      border-radius: 50%;
    }

    #bar:before {
      right: -20px;
    }

    #bar:after {
      right: -38px;
    }

    /* Toast container */
    .toast-container {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 9999;
    }

    /* Toast message style */
    .toast {
      background-color: #333;
      color: #fff;
      padding: 15px;
      margin-bottom: 10px;
      border-radius: 5px;
      font-size: 14px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      opacity: 0;
      transform: translateY(20px);
      transition: opacity 0.5s ease, transform 0.5s ease;
    }

    .toast.show {
      opacity: 1;
      transform: translateY(0);
    }
  </style>
</head>
<body>

  <div id="form-ui">
    <form action="#" method="post" id="form">
      <div id="form-body">
        <div id="welcome-lines">
          <div id="welcome-line-1">Sharing</div>
          <div id="welcome-line-2">Welcome Back, Please Register</div>
        </div>

        <div id="input-area">
          <div class="form-inp">
            <label for="name" class="visually-hidden">Name</label>
            <input id="name" name="name" placeholder="Name" type="text" required>
          </div>

          <div class="form-inp">
            <label for="email" class="visually-hidden">Email</label>
            <input id="email" name="email" placeholder="Email Address" type="email" required>
          </div>

          <div class="form-inp">
            <label for="password" class="visually-hidden">Password</label>
            <input id="password" name="password" placeholder="Password" type="password" required>
          </div>
        </div>

        <div id="submit-button-cvr">
          <button id="submit-button" type="submit">Register</button>
        </div>

        <div id="forgot-pass">
  <a href="login.php">Already have an account?</a>
</div>


        <div id="bar"></div>
      </div>
    </form>
  </div>

  <script>
    document.getElementById('form').addEventListener('submit', function(e) {
      e.preventDefault();  // Prevent the default form submission

      // Get the form data
      const name = document.getElementById('name').value;
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;

      // Create a data object to send to the API
      const data = { name, email, password };

      // Send a POST request to the API
      fetch('http://localhost/document_api/api/user/create.php', {  // Replace with your actual API endpoint
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),  // Send the data as a JSON string
      })
      .then(response => response.json())
      .then(data => {
        if (data.token) {
  localStorage.setItem('auth_token', data.token); // Store the token
  showToast('Registration successful!', 'success');


} else {
  showToast(data.message || 'Registration failed.', 'error');
}

      })
      .catch(error => {
        console.error('Error:', error);
        showToast('There was an error with the registration process.', 'error');
      });
    });

    // Function to show toast message
    function showToast(message, type) {
      const toastContainer = document.createElement('div');
      toastContainer.classList.add('toast-container');
      
      const toast = document.createElement('div');
      toast.classList.add('toast');
      if (type === 'success') {
        toast.style.backgroundColor = '#4CAF50'; // Green for success
      } else {
        toast.style.backgroundColor = '#f44336'; // Red for error
      }
      toast.textContent = message;

      toastContainer.appendChild(toast);
      document.body.appendChild(toastContainer);

      // Show the toast with animation
      setTimeout(() => {
        toast.classList.add('show');
      }, 100);

      // Hide and remove the toast after 4 seconds
      setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
          toastContainer.remove();
        }, 500);
      }, 4000);
    }
  </script>

</body>
</html>
