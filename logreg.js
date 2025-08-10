const container = document.getElementById("container");
const registerBtn = document.getElementById("register");
const loginBtn = document.getElementById("login");

registerBtn.addEventListener("click", () => {
  container.classList.add("active");
});

loginBtn.addEventListener("click", () => {
  container.classList.remove("active");
});

function togglePassword(inputId, toggleBtn) {
  const input = document.getElementById(inputId);
  if (input.type === "password") {
    input.type = "text";
    toggleBtn.textContent = "🙉"; // Open eye emoji
  } else {
    input.type = "password";
    toggleBtn.textContent = "🙈"; // Closed eye emoji
  }
}

document.addEventListener('DOMContentLoaded', function () {
  if (typeof hasRegisterErrors !== 'undefined' && hasRegisterErrors) {
    document.getElementById('container').classList.add('active');

    var popup = document.getElementById('error-popup');
    if (popup) {
      popup.style.display = 'flex';

      document.getElementById('close-error-popup').addEventListener('click', function () {
        popup.style.display = 'none';
      });

      popup.addEventListener('click', function (e) {
        if (e.target === popup) {
          popup.style.display = 'none';
        }
      });
    }
  }
});
