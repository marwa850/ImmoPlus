import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/useAuth';

export default function Navbar() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  const deconnecter = async () => {
    await logout();
    navigate('/');
  };

  return (
    <nav className="navbar">
      <Link to="/" className="logo">🏠 Immo<span>Plus</span></Link>
      <div className="liens">
        <Link to="/">Annonces</Link>
        {user?.role.nom === 'admin' && <Link to="/admin/annonces">Administration</Link>}
        {user ? (
          <>
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