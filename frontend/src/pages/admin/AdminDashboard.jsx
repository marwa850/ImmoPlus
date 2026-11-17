import { useEffect, useState } from 'react';
import api from '../../services/api';
import GraphiqueMensuel from '../../components/GraphiqueMensuel';

const argent = (n) => Number(n).toLocaleString('fr-FR') + ' MAD';

export default function AdminDashboard() {
  const [s, setS] = useState(null);
  const [evolution, setEvolution] = useState([]);
  const [periode, setPeriode] = useState({ debut: '', fin: '' });

  useEffect(() => {
    api.get('/admin/stats').then((r) => setS(r.data));
    api.get('/admin/stats/evolution').then((r) => setEvolution(r.data));
  }, []);

  // Filtre par période (F28)
  const filtrer = (e) => {
    e.preventDefault();
    api.get('/admin/stats/evolution', { params: periode }).then((r) => setEvolution(r.data));
  };

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

      <h2 className="titre-section">Évolution mensuelle</h2>
      <form className="filtres" onSubmit={filtrer}>
        <label>Du <input type="date" value={periode.debut}
          onChange={(e) => setPeriode({ ...periode, debut: e.target.value })} /></label>
        <label>Au <input type="date" value={periode.fin}
          onChange={(e) => setPeriode({ ...periode, fin: e.target.value })} /></label>
        <button className="btn" type="submit">Filtrer</button>
      </form>

      <div className="actions-client">
        <div className="bloc">
          <h2>Ventes par mois</h2>
          <GraphiqueMensuel donnees={evolution} champ="ventes" couleur="#6366f1" />
        </div>
        <div className="bloc">
          <h2>Locations par mois</h2>
          <GraphiqueMensuel donnees={evolution} champ="locations" couleur="#10b981" />
        </div>
      </div>
    </div>
  );
}