function generatePassword(showPlainText = true) {
  const characters =
    "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_-+=";
  const passwordLength = 12;
  let password = "";

  for (let i = 0; i < passwordLength; i++) {
    const randomIndex = Math.floor(Math.random() * characters.length);
    password += characters.charAt(randomIndex);
  }

  return showPlainText ? password : "*".repeat(password.length);
}

function getPasswordChoice() {
  // Clear the stored choice on each invocation
  sessionStorage.removeItem("passwordChoice");

  // Show a confirmation dialog
  const confirmed = window.confirm(
    'Do you want to generate a password? Click "OK" to generate or "Cancel" to enter your own.'
  );

  // Store the choice in sessionStorage for the current session
  sessionStorage.setItem("passwordChoice", confirmed ? "generate" : "");

  return confirmed ? "generate" : "";
}

function togglePasswordVisibility(inputField, checkboxId) {
  const passwordInput = document.getElementById(inputField);
  const checkbox = document.getElementById(checkboxId);

  // Toggle the password field's type between "password" and "text"
  passwordInput.type = checkbox.checked ? "text" : "password";
}

document.addEventListener("DOMContentLoaded", function () {
  const showPasswordCheckbox = document.getElementById("showPasswordCheckbox");

  showPasswordCheckbox.addEventListener("change", function () {
    togglePasswordVisibility("password", "showPasswordCheckbox");
    togglePasswordVisibility("confirm_password", "showPasswordCheckbox");
  });
});

var $attempt = 0;
function setPassword(inputField, showPasswordCheckboxId) {
  if ($attempt == 0) {
    const choice = getPasswordChoice();
    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById("confirm_password");
    const showPasswordCheckbox = document.getElementById(
      showPasswordCheckboxId
    );

    if (choice === "generate") {
      const generatedPassword = generatePassword();
      passwordInput.value = generatedPassword;
      confirmPasswordInput.value = generatedPassword;

      // Focus on the password field after setting the generated password
      passwordInput.focus();

      // Update the checkbox state
      showPasswordCheckbox.checked = false;
      togglePasswordVisibility("password", showPasswordCheckboxId);
      togglePasswordVisibility("confirm_password", showPasswordCheckboxId);
    } else {
      // Allow the user to enter their own password
      inputField.value = "";
    }
    $attempt = 1;
  }
}

function checkPasswordStrength(password) {
    if (password.trim() === "") {
        document.getElementById('password-strength').textContent = '';
        return;
      }

  // Define your password strength criteria
  // For example, you can check length, presence of uppercase/lowercase letters, numbers, and special characters
  let strength = 0;

  if (password.length >= 8) {
    strength += 1;
  }

  if (/[A-Z]/.test(password)) {
    strength += 1;
  }

  if (/[a-z]/.test(password)) {
    strength += 1;
  }

  if (/\d/.test(password)) {
    strength += 1;
  }

  if (/[\W_]/.test(password)) {
    strength += 1;
  }

  // Display feedback based on the strength
  let feedback = "";
  let color = "";
  switch (strength) {
    case 0:
    case 1:
      feedback = "Weak";
      color = "red";
      break;
    case 2:
      feedback = "Moderate";
      color = "yellow";
      break;
    case 3:
    case 4:
      feedback = "Strong";
      color = "blue";
      break;
    case 5:
      feedback = "Very Strong";
      color = "green";
      break;
    default:
      feedback = "";
  }

  document.getElementById("password-strength").textContent = feedback;
  document.getElementById("password-strength").style.color = color;
}
