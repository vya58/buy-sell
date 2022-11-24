'use strict';

(function () {
  var form = document.querySelector('.form');

  if (form) {
    var formFields = form.querySelectorAll('.js-field');
    var formButton = form.querySelector('.js-button');
    var userPassword = form.querySelector('[name="user-password"]');
    var userPasswordAgain = form.querySelector('[name="user-password-again"]');
    var userNameField = form.querySelector('[name="user-name"]');
    var userEmailField = form.querySelector('[name="user-email"]');
    var REGEX_NAME = /[^a-zA-Zа-яёА-ЯЁ\-\s]/g;

    var validateNameInput = function (formElement) {
      var RegEx = REGEX_NAME;
      if (RegEx) {
        formElement.addEventListener('input', () => {
          formElement.value = formElement.value.replace(RegEx, '');
        });
        return;
      }
    };

    //валидация поля email
    var validateEmailInput = function (input) {
      var flag = true;
      var emailString = /[a-zA-Zа-яёА-ЯЁ0-9]{1}([a-zA-Zа-яёА-ЯЁ0-9\-_\.]{1,})?@[a-zA-Zа-яёА-ЯЁ0-9\-]{1}([a-zA-Zа-яёА-ЯЁ0-9.\-]{1,})?[a-zA-Zа-яёА-ЯЁ0-9\-]{1}\.[a-zA-Zа-яёА-ЯЁ]{2,6}/;
      var regEmail = new RegExp(emailString, '');
      if (regEmail.test(input.value)) {
        input.parentNode.classList.remove('form__field--invalid');
      } else {
        input.parentNode.classList.add('form__field--invalid');
        flag = false;
      }
      return flag;
    };

    var doPasswordsMatch = function () {
      if (userPassword.value === userPasswordAgain.value) {
        userPasswordAgain.parentNode.classList.remove('form__field--invalid');
        return true;
      } else {
        userPasswordAgain.parentNode.classList.add('form__field--invalid');
        formButton.setAttribute('disabled', 'disabled');
        return false;
      }
    };

    if (userNameField && userEmailField && userPassword && userPasswordAgain) {
      // включаем ограничения для ввода поля имя пользователя
      validateNameInput(userNameField);

      userEmailField.addEventListener('input', function () {
        validateEmailInput(userEmailField);
      });

      Array.prototype.slice.call(formFields).forEach(function (field) {
        field.addEventListener('input', function () {
          setFillField(field);
          if (formButton) {
            if (getFillFields() && setTimeout(doPasswordsMatch, 1000)) {
              formButton.removeAttribute('disabled');
            } else {
              formButton.setAttribute('disabled', 'disabled');
            }
          }
        });
      });
    }

    var setFillField = function (field) {
      if (field.value) {
        field.parentNode.classList.add('form__field--fill');
      } else {
        field.parentNode.classList.remove('form__field--fill');
      }
      field.addEventListener('focus', function () {
        field.parentNode.classList.add('form__field--focus');
      });
      field.addEventListener('blur', function () {
        field.parentNode.classList.remove('form__field--focus');
      });
    };

    var getFillFields = function () {
      var fill = true;
      for (var i = 0; i < formFields.length; i++) {
        if (!formFields[i].value) {
          fill = false;
          break;
        }
      }
      return fill;
    };

    Array.prototype.slice.call(formFields).forEach(function (field) {
      setFillField(field);
    });

    Array.prototype.slice.call(formFields).forEach(function (field) {
      field.addEventListener('input', function () {
        setFillField(field);
        if (formButton) {
          if (getFillFields()) {
            formButton.removeAttribute('disabled');
          } else {
            formButton.setAttribute('disabled', 'disabled');
          }
        }
      });
    });

    var selects = document.querySelectorAll('.js-multiple-select');
    for (var i = 0; i < selects.length; i++) {
      var placeholder = selects[i].getAttribute('data-label');
      var SS = new Selectr(selects[i], {
        searchable: false,
        multiple: true,
        width: 222,
        placeholder: placeholder
      });
      var selection = Selectr.prototype.select,
        deselection = Selectr.prototype.deselect;
      var ours = document.createElement('div');
      ours.className = SS.selected.className;
      SS.selected.className += ' selectr-selected--hidden';
      SS.selected.parentNode.insertBefore(ours,SS.selected);
      var updateOurs = function(){
        ours.innerText = SS.selected.innerText.trim().replace(/\n/g, ', ') || placeholder;
      };
      Selectr.prototype.select = function(){
        selection.apply(this, arguments);
        updateOurs();
      };

      Selectr.prototype.deselect = function(){
        deselection.apply(this, arguments);
        updateOurs();
      };
      updateOurs();
    }

    var priceField = form.querySelector('.js-price');
    if (priceField) {
      priceField.addEventListener('keydown', function(e) {
        if (window.event.keyCode >= 65 && window.event.keyCode <= 90 || window.event.keyCode === 189 || window.event.keyCode === 188) {
          e.preventDefault();
        }
        if (window.event.keyCode === 190 && (!priceField.value || priceField.value.includes('.'))) {
          e.preventDefault();
        }
      })
    }
  }

})();
