import { useState } from 'react';
import api from '../services/api';
import { AuthContext } from './contexte';

export function AuthProvider({ children }) {
  // On relit l'utilisateur gardé dans le navigateur (reste connecté après rechargement)
  const [user, setUser] = useState(() => {
    const sauvegarde = localStorage.getItem('user');
    return sauvegarde ? JSON.parse(sauvegarde) : null;
  });

  // Garde le token et l'utilisateur après connexion ou inscription
  const enregistrerSession = (data) => {
    localStorage.setItem('token', data.token);
    localStorage.setItem('user', JSON.stringify(data.user));
    setUser(data.user);
  };

  const login = async (email, mot_de_passe) => {
    const reponse = await api.post('/login', { email, mot_de_passe });
    enregistrerSession(reponse.data);
    return reponse.data.user;
  };

  const register = async (formulaire) => {
    const reponse = await api.post('/register', formulaire);
    enregistrerSession(reponse.data);
    return reponse.data.user;
  };

  const logout = async () => {
    try {
      await api.post('/logout');
    } finally {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      setUser(null);
    }
  };

  return (
    <AuthContext.Provider value={{ user, login, register, logout }}>
      {children}
    </AuthContext.Provider>
  );
}