import React, { useState } from 'react';

const RegistrationForm = () => {
  const [username, setUsername] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [nickname, setNickname] = useState(''); // Dodaj stan dla nickname

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch('http://localhost:8000/users/', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ username, email, password, nickname }), // Dodaj nickname do obiektu przesyłanego w ciele żądania
      });
      if (!response.ok) {
        throw new Error('Registration failed');
      }
      // Handle successful registration
      console.log('Registration successful');
    } catch (error) {
      console.error('Error registering:', error.message);
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <input
        type="text"
        placeholder="Username"
        value={username}
        onChange={(e) => setUsername(e.target.value)}
      />
      <input
        type="email"
        placeholder="Email"
        value={email}
        onChange={(e) => setEmail(e.target.value)}
      />
      <input
        type="text" // Zmien typ na text dla nickname
        placeholder="Nickname"
        value={nickname}
        onChange={(e) => setNickname(e.target.value)} // Dodaj obsługę zmiany dla nickname
      />
      <input
        type="password"
        placeholder="Password"
        value={password}
        onChange={(e) => setPassword(e.target.value)}
      />
      <button type="submit">Register</button>
    </form>
  );
};

export default RegistrationForm;