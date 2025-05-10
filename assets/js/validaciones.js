class FormValidator {
  constructor(formSelector) {
    this.form = document.querySelector(formSelector);
    this.inputs = this.form.querySelectorAll("input[type='text']");

    this.init();
  }

  init() {
    this.inputs.forEach(input => {
      input.setAttribute("minlength", "3");
      input.setAttribute("maxlength", "50");

      input.addEventListener("keypress", (e) => {
        const regex = /[a-zA-Z\s]/;
        if (!regex.test(e.key)) {
          e.preventDefault();
        }
      });
    });
  }
}

class PasswordValidator {
  constructor(selector) {
    this.input = document.querySelector(selector);
    this.form = this.input.closest("form");
    this.init();
  }

  init() {
    this.input.addEventListener("input", () => this.liveFeedback());
    this.form.addEventListener("submit", (e) => {
      if (!this.validatePassword()) {
        e.preventDefault();
        alert("La contraseña no cumple con todos los requisitos.");
      }
    });
  }

  liveFeedback() {
    const value = this.input.value;
    this.toggleRequirement("req-length", value.length >= 8);
    this.toggleRequirement("req-mayus", /[A-Z]/.test(value));
    this.toggleRequirement("req-minus", /[a-z]/.test(value));
    this.toggleRequirement("req-num", /\d/.test(value));
    this.toggleRequirement("req-esp", /[^a-zA-Z0-9]/.test(value));
  }

  toggleRequirement(id, valid) {
    const el = document.getElementById(id);
    if (el) {
      el.textContent = (valid ? "✅" : "❌") + " " + el.textContent.slice(2);
    }
  }

  validatePassword() {
    const value = this.input.value;
    return (
      value.length >= 8 &&
      /[A-Z]/.test(value) &&
      /[a-z]/.test(value) &&
      /\d/.test(value) &&
      /[^a-zA-Z0-9]/.test(value)
    );
  }
}

document.addEventListener("DOMContentLoaded", () => {
  new FormValidator(".usuarioAlta");
  new PasswordValidator(".pass_input");

  const toggleBtn = document.getElementById("togglePassword");
  const toggleIcon = document.getElementById("toggleIcon");
  const password = document.getElementById("passwordField");

  if (toggleBtn && toggleIcon && password) {
    toggleBtn.addEventListener("click", () => {
      const isPassword = password.getAttribute("type") === "password";
      password.setAttribute("type", isPassword ? "text" : "password");
      toggleIcon.classList.toggle("bi-eye");
      toggleIcon.classList.toggle("bi-eye-slash");
    });
  }
});
