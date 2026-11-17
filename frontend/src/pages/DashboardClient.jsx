import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import api from '../services/api';
import { useAuth } from '../context/useAuth';

export default function DashboardClient() {
  const { user } = useAuth();
  const [s, setS] = useState(null);

  useEffect(() => {
    api.get('/stats/client').then((r) => setS(r.data));
  }, []);

  if (!s) return <p className="vide">Chargement...</p>;

  return (
    <div className="admin">
      <h1>Bonjour {user.prenom} 👋</h1>

      <div className="cartes-stats">
        <Link to="/favoris" className="stat"><span className="stat-icone">❤️</span><strong>{s.favoris}</strong><small>Favoris</small></Link>
        <Link to="/mes-demandes" className="stat"><span className="stat-icone">📅</span><strong>{s.visites}</strong><small>Visites ({s.visites_attente} en attente)</small></Link>
        <Link to="/mes-demandes" className="stat"><span className="stat-icone">💰</span><strong>{s.offres}</strong><small>Offres ({s.offres_attente} en attente)</small></Link>
        <Link to="/mes-demandes" className="stat"><span className="stat-icone">🎉</span><strong>{s.offres_acceptees}</strong><small>Offres acceptées</small></Link>
      </div>
    </div>
  );
}