import { useContext } from 'react';
import { AuthContext } from './contexte';

// Raccourci pour utiliser le contexte dans les pages
export const useAuth = () => useContext(AuthContext);