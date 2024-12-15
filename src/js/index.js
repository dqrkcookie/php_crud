function switchForms() {
  const login = document.querySelector('.login');
  const signup = document.querySelector('.signup');
  const go_signup = document.getElementById('go_signup');
  const go_back = document.getElementById('go_back');

  go_signup.addEventListener('click', () => {
    login.style.display = 'none';
    signup.style.display = 'block';
  });

  go_back.addEventListener('click', () => {
    login.style.display = 'block';
    signup.style.display = 'none';
  });
}

switchForms();
