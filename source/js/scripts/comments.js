'use strict';

(function() {
  var deletEls = document.querySelectorAll('.js-delete');
  for (var i = 0; i < deletEls.length; i++) {
    deletEls[i].addEventListener('click', function() {
      var card = this.closest('.js-card');
      card.parentNode.removeChild(card);

    })
  }
})();
