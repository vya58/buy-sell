// Подключение к Firebase Realtime Database и её инициализация:

// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.15.0/firebase-app.js";

// Импорт web app's Firebase configuration
import { firebaseConfig } from './firebaseConfig.js';

import { getDatabase, ref, onValue, update } from "https://www.gstatic.com/firebasejs/9.15.0/firebase-database.js";

// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// Конфигурация 'firebaseConfig' перенесена в firebaseConfig.js, чтобы не держать ключи в открытом доступе

// Initialize Firebase
const app = initializeApp(firebaseConfig);
// Initialize Realtime Database and get a reference to the service
const database = getDatabase(app);

// Работа чата с Firebase Realtime Database:

const chatConversation = document.querySelector('.chat__conversation');
const offerId = document.querySelector('.ticket__title').getAttribute('data-attr');
const buyerId = document.querySelector('.chat__conversation').getAttribute('data-buyer-id');
const addressee = document.querySelector('.chat__subtitle');
const chatButton = document.querySelector('.chat-button');

// Reference, представляющая расположение в базе данных, соответствующее предоставленному пути.
// Структура Firebase Realtime Database чата: Firebase/номер_обявления/id_покупателя/порядковый_номер_сообщения
const starCountRef = ref(database, offerId + '/' + buyerId);

// Включение прослушивателя изменений в Firebase на кнопку открытия окна чата
chatButton.addEventListener('click', () => {
  firebaseChangeListener(starCountRef, buyerId);
});

// Включение прослушивателя изменений в Firebase на возвращение пользователя к вкладке браузера с открытым чатом, чтобы сообщения не отмечались как прочитанные, если пользователь не на странице с чатом
window.addEventListener('focus', function () {
  firebaseChangeListener(starCountRef, buyerId);
});

// Функция прослушивания изменений в Firebase
// Если нет buyerId, значит продавец отправляет сообщение сам себе
function firebaseChangeListener(starCountRef, buyerId = null) {
  if (buyerId) {

    console.log(buyerId);
    // Прослушиватель изменений в Firebase
    onValue(starCountRef, (snapshot) => {
      const data = snapshot.val();

      if (data && document.hasFocus()) {
        renderMessage(data);

        // Таймаут на установку сообщению статуса "Прочитано" после получения
        setTimeout(() => {
          data.some((element, i) => {
            const updates = {}

            // Обновление статуса "Прочитано" в Firebase
            if (!element.read && Number(element.fromUserId) === Number(addressee.dataset.receiverId)) {
              var messageData = {
                date: element.date,
                message: element.message,
                read: true,
                offerId: element.offerId,
                toUserId: element.toUserId,
                fromUserId: element.fromUserId,
              };
              updates[offerId + '/' + buyerId + '/' + i + '/'] = messageData;

              update(ref(database), updates);
            }
          });
        }, 5000, data);
      }
    });
  }

}

// Создаём пустой DocumentFragment и получаем шаблон миниатюр
const messagesListFragment = document.createDocumentFragment();
const messageTemplate = document.querySelector('#chat__message').content;

// Генерируем сообщения чата из шаблона  и сохраняем в DocumentFragment
function renderMessage(data, amount = data.length) {
  data.some((element, i) => {
    const clonedMessage = messageTemplate.cloneNode(true);

    if (Number(element.fromUserId) === Number(addressee.dataset.receiverId)) {
      clonedMessage.querySelector('.chat__message-author').textContent = addressee.dataset.receiverName;
    } else {
      clonedMessage.querySelector('.chat__message-author').textContent = 'Вы';
    }

    const messageTime = new Date(Date.parse(element.date));

    const messageDate = messageTime.toLocaleDateString('ru-RU', { year: 'numeric', month: 'long', day: 'numeric' });

    // Если сообщение послано сегодня, то выводится только время (часы:минуты)
    let messageTimeText = messageTime.toLocaleTimeString('ru-RU', { hour12: false, hour: "numeric", minute: "numeric" });

    // Выведение полной даты (месяц прописью), если выводится сообщение, посланное не сегодня
    if (messageDate !== new Date().toLocaleDateString('ru-RU', { year: 'numeric', month: 'long', day: 'numeric' })) {
      messageTimeText = messageDate + ' ' + messageTimeText;
    }

    clonedMessage.querySelector('.chat__message-time').textContent = messageTimeText;
    clonedMessage.querySelector('.chat__message-content').textContent = element.message;

    // Выделение непрочитанных сообщений
    if (!element.read) {
      clonedMessage.querySelector('.chat__message').classList.add('unread');
    }
    messagesListFragment.appendChild(clonedMessage);
  });

  // Удаление старых сообщений
  const oldMessages = chatConversation.querySelectorAll('.chat__message');

  oldMessages.forEach((oldMessage) => {
    oldMessage.remove();
  });

  // Вставка полученной коллекции сообщений в блок-контейнер '.chat__conversation'
  chatConversation.append(messagesListFragment);

  // Прокрутка сообщений чата вниз, к последнему
  scrollMessage();
}

// Скролл сообщений вниз в окне чата к самому последнему
function scrollMessage() {
  const block = document.querySelector('.chat__conversation');
  block.scrollTop = block.scrollHeight;
}

// Скролл сообщений вниз в окне чата к самому последнему после ajax
// https://github.com/yiisoft/jquery-pjax
$(document).on('pjax:complete', function () {
  scrollMessage()
})

scrollMessage();
