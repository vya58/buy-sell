'use strict';

(function () {
  var signUpAvatarContainer = document.querySelector('.js-preview-container');

  if (signUpAvatarContainer) {
    var signUpFieldAvatarInput = signUpAvatarContainer.querySelector('.js-file-field');
    var signUpAvatar = signUpAvatarContainer.querySelector('.js-preview');

    var readFilePhoto = function (file) {
      var reader = new FileReader();
      reader.addEventListener('load', function () {
        var image = document.createElement('img');
        image.src = reader.result;
        signUpAvatar.innerHTML = '';
        signUpAvatar.appendChild(image);
      });
      reader.readAsDataURL(file);
    };

    signUpFieldAvatarInput.addEventListener('change', function () {
      var file = signUpFieldAvatarInput.files[0];
      readFilePhoto(file);
      signUpAvatarContainer.classList.add('uploaded');
    });
  }
})();
