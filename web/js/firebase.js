
// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.15.0/firebase-app.js";

// Импорт web app's Firebase configuration
import { firebaseConfig } from './firebaseConfig.js';

// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// Перенесено в firebaseConfig.js, чтобы не держать ключи в открытом доступе

// Initialize Firebase
const app = initializeApp(firebaseConfig);
