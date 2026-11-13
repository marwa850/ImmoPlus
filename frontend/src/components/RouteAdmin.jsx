import { Navigate } from 'react-router-dom';
import { useAuth } from '../context/useAuth';

// Affiche la page seulement si l'utilisateur est administrateur
export default function RouteAdmin({ children }) {
  const { user } = useAuth();

  if (!user) return <Navigate to="/connexion" />;
  if (user.role.nom !== 'admin') return <Navigate to="/" />;

  return children;
}