'use strict';

(function () {
  var search = document.querySelector('.search');

  if (search) {
    var searchInput = search.querySelector('.search input');
    var searchCloseButton = search.querySelector('.search__close-btn');

    if (searchInput.value) {
      search.classList.add('search--active');
      searchCloseButton.classList.add('search__close-btn--active');
    } else {
      search.classList.remove('search--active');
      searchCloseButton.classList.remove('search__close-btn--active');
    }

    searchInput.addEventListener('change', function () {
      if (searchInput.value) {
        search.classList.add('search--active');
      } else {
        search.classList.remove('search--active');
      }
    });

    searchInput.addEventListener('input', function () {
      if (searchInput.value) {
        searchCloseButton.classList.add('search__close-btn--active');
      } else {
        searchCloseButton.classList.remove('search__close-btn--active');
      }
    });

    searchCloseButton.addEventListener('click', function () {
      searchCloseButton.classList.remove('search__close-btn--active');
      searchInput.value = '';
      search.classList.remove('search--active');
    });
  }
})();
