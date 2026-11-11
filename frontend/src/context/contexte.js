import { createContext } from 'react';

// Le contexte partagé : il contient l'utilisateur connecté et les fonctions login/register/logout
export const AuthContext = createContext();