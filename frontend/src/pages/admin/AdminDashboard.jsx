import { useEffect, useState } from 'react';
import api from '../../services/api';

const argent = (n) => Number(n).toLocaleString('fr-FR') + ' MAD';

export default function AdminDashboard() {
  const [s, setS] = useState(null);

  useEffect(() => {
    api.get('/admin/stats').then((r) => setS(r.data));
  }, []);

  if (!s) return <p className="vide">Chargement...</p>;

  const cartes = [
    ['🏠', 'Biens disponibles', s.disponibles],
    ['🔒', 'Biens réservés', s.reservees],
    ['✅', 'Biens vendus', s.vendues],
    ['🔑', 'Biens loués', s.louees],
    ['📅', 'Visites', s.visites],
    ['💰', 'Offres', s.offres],
    ['📈', "Taux d'acceptation", s.taux + ' %'],
  ];

  return (
    <div className="admin">
      <h1>Tableau de bord</h1>

      <div className="cartes-stats">
        {cartes.map(([icone, nom, valeur]) => (
          <div className="stat" key={nom}>
            <span className="stat-icone">{icone}</span>
            <strong>{valeur}</strong>
            <small>{nom}</small>
          </div>
        ))}
      </div>

      <div className="actions-client">
        <div className="bloc">
          <h2>Total des ventes</h2>
          <p className="prix grand">{argent(s.total_ventes)}</p>
          <small>Somme des offres acceptées (biens à vendre)</small>
        </div>
        <div className="bloc">
          <h2>Total des loyers mensuels</h2>
          <p className="prix grand">{argent(s.total_loyers)}</p>
          <small>Somme des offres acceptées (biens à louer)</small>
        </div>
      </div>
    </div>
  );
}