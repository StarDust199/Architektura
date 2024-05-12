import React from 'react';
import LoginForm from './LoginForm';
import RegistrationForm from './RegistrationForm';

const App = () => {
  return (
    <div>
      <h1>Login</h1>
      <LoginForm />
      <h1>Registration</h1>
      <RegistrationForm />
    </div>
  );
};

export default App;