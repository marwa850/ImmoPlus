import { useEffect, useState } from 'react';
import api from '../services/api';

// Petit badge coloré pour afficher un statut
const Statut = ({ valeur }) => <span className={`statut-badge ${valeur}`}>{valeur.replace('_', ' ')}</span>;

export default function MesDemandes() {
  const [visites, setVisites] = useState([]);
  const [offres, setOffres] = useState([]);

  const charger = () => {
    api.get('/visites').then((r) => setVisites(r.data));
    api.get('/offres').then((r) => setOffres(r.data));
  };

  useEffect(() => {
    charger();
  }, []);

  const annuler = async (id) => {
    await api.patch(`/visites/${id}/annuler`);
    charger();
  };

  return (
    <div className="admin">
      <h1>Mes visites</h1>
      <table className="tableau">
        <thead><tr><th>Bien</th><th>Date</th><th>Statut</th><th></th></tr></thead>
        <tbody>
          {visites.length === 0 && <tr><td colSpan="4">Aucune visite.</td></tr>}
          {visites.map((v) => (
            <tr key={v.id}>
              <td>{v.annonce.titre} ({v.annonce.bien.ville})</td>
              <td>{new Date(v.date_visite).toLocaleString('fr-FR')}</td>
              <td><Statut valeur={v.statut} /></td>
              <td>
                {['en_attente', 'confirmee'].includes(v.statut) && (
                  <button className="btn btn-danger" onClick={() => annuler(v.id)}>Annuler</button>
                )}
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      <h1 style={{ marginTop: 40 }}>Mes offres</h1>
      <table className="tableau">
        <thead><tr><th>Bien</th><th>Montant</th><th>Date</th><th>Statut</th></tr></thead>
        <tbody>
          {offres.length === 0 && <tr><td colSpan="4">Aucune offre.</td></tr>}
          {offres.map((o) => (
            <tr key={o.id}>
              <td>{o.annonce.titre} ({o.annonce.bien.ville})</td>
              <td>{Number(o.montant).toLocaleString('fr-FR')} MAD</td>
              <td>{new Date(o.date_offre).toLocaleDateString('fr-FR')}</td>
              <td><Statut valeur={o.statut} /></td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}