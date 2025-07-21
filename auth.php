<?php
session_start(); // Oturum başlat

// Kullanıcı adı ve şifre doğrulama işlemini burada yapın (örneğin veritabanına bağlanarak)
$username = $_POST['selam']; // Giriş yaparken gönderilen kullanıcı adı

// Başarılı giriş sonrası çerez oluşturma
if ($username) {
    // Çerez oluşturuluyor: 30 gün boyunca geçerli olacak
    setcookie("username", $username, time() + (30 * 24 * 60 * 60), "/"); // Çerez için path parametresi belirliyoruz
    
    // Giriş işlemi başarılı ise kullanıcıyı ana sayfaya yönlendiriyoruz
    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Kayıt / Giriş</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
  <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
    <h2 id="form-title" class="text-xl font-semibold mb-4 text-center">Giriş Yap</h2>
    <div id="message" class="mb-4 p-2 rounded hidden"></div>

    <form id="authForm" class="space-y-4">
      <input type="text" name="username" placeholder="Kullanıcı Adı" class="w-full p-2 border rounded" required>
      <input type="password" name="password" placeholder="Parola" class="w-full p-2 border rounded" required>

      <div id="gender-field" style="display: none;">
        <select name="gender" class="w-full p-2 border rounded">
          <option value="">Cinsiyet Seçin</option>
          <option value="male">Erkek</option>
          <option value="female">Kadın</option>
          <option value="other">Diğer</option>
        </select>
      </div>

      <button type="button" onclick="submitForm()" id="submitBtn" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Giriş Yap</button>
    </form>

    <p class="mt-4 text-center">
      <span id="toggle-text">Hesabınız yok mu?</span>
      <button onclick="toggleMode()" class="text-blue-600 underline" id="toggleBtn">Kayıt Ol</button>
    </p>
  </div>

  <script>
    let mode = 'login'; // veya 'register'

    function toggleMode() {
      mode = (mode === 'login') ? 'register' : 'login';

      const genderField = document.getElementById('gender-field');
      const title = document.getElementById('form-title');
      const submitBtn = document.getElementById('submitBtn');
      const toggleBtn = document.getElementById('toggleBtn');
      const toggleText = document.getElementById('toggle-text');

      if (mode === 'register') {
        genderField.style.display = 'block';
        title.textContent = 'Kayıt Ol';
        submitBtn.textContent = 'Kayıt Ol';
        toggleBtn.textContent = 'Giriş Yap';
        toggleText.textContent = 'Zaten bir hesabınız var mı?';
      } else {
        genderField.style.display = 'none';
        title.textContent = 'Giriş Yap';
        submitBtn.textContent = 'Giriş Yap';
        toggleBtn.textContent = 'Kayıt Ol';
        toggleText.textContent = 'Hesabınız yok mu?';
      }

      clearMessage();
    }

    function showMessage(text, success = false) {
      const msg = document.getElementById('message');
      msg.textContent = text;
      msg.className = `mb-4 p-2 rounded ${success ? 'bg-green-100 text-green-800 border-l-4' : 'bg-red-100 text-red-800 border-l-4'}`;
      msg.classList.remove('hidden');
    }

    function clearMessage() {
      const msg = document.getElementById('message');
      msg.classList.add('hidden');
      msg.textContent = '';
    }

    async function submitForm() {
      const form = document.getElementById('authForm');
      const formData = new FormData(form);

      const data = {
        action: mode,
        username: formData.get('username'),
        password: formData.get('password')
      };

      if (mode === 'register') {
        data.gender = formData.get('gender');
        if (!data.gender) {
          showMessage("Lütfen cinsiyet seçiniz.");
          return;
        }
      }

      const res = await fetch('api/users/auth_api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
      });

      const result = await res.json();
      showMessage(result.message, result.status === 'success');
    }
  </script>
</body>
</html>
