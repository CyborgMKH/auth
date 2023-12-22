function checkPasswordStrength(password, id) {
  if (password.trim() === "") {
    document.getElementById("password-strength").textContent = "";
    return;
  }
  // Defining password strength criteria
  // For example, we can check length, presence of uppercase/lowercase letters, numbers, and special characters
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
  document.getElementById(id).textContent = feedback;
  document.getElementById(id).style.color = color;
}
