# 🏫 SakuBK / CareSDQ - Sistem Informasi Kuesioner SDQ Siswa

Aplikasi web berbasis **Laravel 12** yang dirancang untuk membantu sekolah melakukan skrining awal mengenai kekuatan dan kesulitan perilaku siswa menggunakan metode **SDQ (Strengths and Difficulties Questionnaire)**. 

Sistem ini memfasilitasi komunikasi dan analisis data antara **Siswa** (sebagai pengisi kuesioner) dan **Guru BK / Bimbingan Konseling** (sebagai evaluator dan pemantau perkembangan siswa).

## ✨ Fitur Utama
* **Autentikasi & Multi-role:** Pemisahan hak akses antara Siswa dan Guru BK.
* **Isi Kuesioner SDQ:** Halaman pengisian instrumen SDQ yang interaktif dan ramah pengguna bagi Siswa.
* **Dashboard Siswa:** Ringkasan hasil tes dan riwayat pengerjaan mandiri.
* **Dashboard Guru BK:** Panel pemantauan hasil tes seluruh siswa, pencarian riwayat, serta detail diagnosis skor SDQ (kategori perilaku, kesulitan, kekuatan).
* **Klasifikasi Risiko AI:** Integrasi awal klasifikasi tingkat risiko berbasis data kuesioner siswa.

## 🛠️ Tech Stack & Dependencies
* **Framework Backend:** Laravel 12.x ([composer.json](file:///c:/Users/KIEL/shania-app/composer.json))
* **Runtime PHP:** ^8.2
* **Frontend:** Blade, TailwindCSS / Vanilla CSS, Vite ([vite.config.js](file:///c:/Users/KIEL/shania-app/vite.config.js))
* **Database:** MySQL / SQLite
