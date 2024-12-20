function switchForms() {
  const login = document.querySelector('.login');
  const signup = document.querySelector('.signup');
  const go_signup = document.getElementById('go_signup');
  const go_back = document.getElementById('go_back');
  const admin = document.getElementById('admin_btn');
  const go_to_admin = document.getElementById('loginAdmin');

  go_signup.addEventListener('click', () => {
    login.style.display = 'none';
    signup.style.display = 'block';
    go_to_admin.style.display = 'none';
  });

  go_back.addEventListener('click', () => {
    login.style.display = 'block';
    signup.style.display = 'none';
    go_to_admin.style.display = 'none';
  });

  admin.addEventListener('click', () => {
    login.style.display = 'none';
    signup.style.display = 'none';
    go_to_admin.style.display = 'block';
  });
}

switchForms();

function hide() {
  const sure = document.getElementById('sure');

  sure.style.display = 'none';

  window.location.href = '../pages/main.php';
}
