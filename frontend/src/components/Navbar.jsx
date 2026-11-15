import { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/useAuth';
import api from '../services/api';

export default function Navbar() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const [nonLues, setNonLues] = useState(0);

  // Compte les notifications non lues (toutes les 15 secondes)
  useEffect(() => {
    if (!user) return;
    const verifier = () => api.get('/notifications/non-lues').then((r) => setNonLues(r.data.total));
    verifier();
    const minuteur = setInterval(verifier, 15000);
    return () => clearInterval(minuteur);
  }, [user]);

  const deconnecter = async () => {
    await logout();
    navigate('/');
  };

  return (
    <nav className="navbar">
      <Link to="/" className="logo">🏠 Immo<span>Plus</span></Link>
      <div className="liens">
        <Link to="/">Annonces</Link>

        {user?.role.nom === 'client' && <Link to="/favoris">Mes favoris</Link>}
        {user?.role.nom === 'client' && <Link to="/mes-demandes">Mes demandes</Link>}

        {user?.role.nom === 'admin' && <Link to="/admin/annonces">Administration</Link>}
        {user?.role.nom === 'admin' && <Link to="/admin/demandes">Demandes</Link>}

        {user ? (
          <>
            <Link to="/notifications" className="cloche">
              🔔{nonLues > 0 && <span className="pastille">{nonLues}</span>}
            </Link>
            <span className="bonjour">Bonjour {user.prenom}</span>
            <button className="btn btn-clair" onClick={deconnecter}>Déconnexion</button>
          </>
        ) : (
          <>
            <Link to="/connexion">Connexion</Link>
            <Link to="/inscription" className="btn">Inscription</Link>
          </>
        )}
      </div>
    </nav>
  );
}