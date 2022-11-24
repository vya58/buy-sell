'use strict';

(function () {
  var chat = document.querySelector('.chat');
  var buttonToOpenChat = document.querySelector('.chat-button');
  var pageFooter = document.querySelector('.page-footer');

  function openChat() {
    chat.classList.remove('visually-hidden');
    chat.classList.add('chat--open');
    userChatField.focus();
  }

  function closeChat() {
    chat.classList.remove('chat--open');
    chat.classList.add('visually-hidden');
  }

  if (chat && buttonToOpenChat && pageFooter) {
    var userChatField = chat.querySelector('[name="chat-message"]');

    document.addEventListener('click', function (evt) {
      evt.preventDefault();
      if (evt.target === buttonToOpenChat) {
        evt.preventDefault();
        openChat();
      }
      if (!evt.target.closest('.chat') && !evt.target.closest('.chat-button')) {
        closeChat();
      }
    });

    window.addEventListener('keydown', function (evt) {
      if (evt.keyCode === 27) {
        closeChat();
      }
    });

    var pageHeight = document.body.clientHeight;
    var currentWindowHeight = window.innerHeight;
    var footerHeight = pageFooter.clientHeight;
    var lastKnownScrollPosition = 0;
    var valueOfReachingFooter = pageHeight - currentWindowHeight - footerHeight;
    var maxScrollPosition = pageHeight - currentWindowHeight;
    var amendment = 0;
    var INDENT = 49;

    document.addEventListener('scroll', function () {
      lastKnownScrollPosition = window.scrollY;

      if (lastKnownScrollPosition > valueOfReachingFooter) {
        amendment = maxScrollPosition - lastKnownScrollPosition;
        buttonToOpenChat.style.bottom = footerHeight + INDENT - amendment + 'px';
        chat.style.bottom = footerHeight + INDENT - amendment + 'px';
      }
    });
  }
})();
