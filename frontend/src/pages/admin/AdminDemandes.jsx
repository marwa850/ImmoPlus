import { useEffect, useState } from 'react';
import api from '../../services/api';

const Statut = ({ valeur }) => <span className={`statut-badge ${valeur}`}>{valeur.replace('_', ' ')}</span>;

export default function AdminDemandes() {
  const [visites, setVisites] = useState([]);
  const [offres, setOffres] = useState([]);
  const [erreur, setErreur] = useState('');

  const charger = () => {
    api.get('/admin/visites').then((r) => setVisites(r.data));
    api.get('/admin/offres').then((r) => setOffres(r.data));
  };

  useEffect(() => {
    charger();
  }, []);

  // Confirmer / refuser / annuler une visite
  const traiterVisite = async (id, statut) => {
    await api.patch(`/admin/visites/${id}`, { statut });
    charger();
  };

  // Reprogrammer : on demande une nouvelle date
  const reprogrammer = async (id) => {
    const date = window.prompt('Nouvelle date (format AAAA-MM-JJ HH:MM)');
    if (!date) return;
    try {
      await api.patch(`/admin/visites/${id}`, { statut: 'confirmee', date_visite: date });
      setErreur('');
      charger();
    } catch {
      setErreur('Date invalide (elle doit être dans le futur)');
    }
  };

  // Accepter / refuser une offre
  const traiterOffre = async (id, statut) => {
    try {
      await api.patch(`/admin/offres/${id}`, { statut });
      setErreur('');
    } catch (err) {
      setErreur(err.response?.data?.message || 'Erreur');
    }
    charger();
  };

  return (
    <div className="admin">
      {erreur && <p className="erreur">{erreur}</p>}

      <h1>Demandes de visite</h1>
      <table className="tableau">
        <thead><tr><th>Client</th><th>Bien</th><th>Date</th><th>Statut</th><th>Actions</th></tr></thead>
        <tbody>
          {visites.length === 0 && <tr><td colSpan="5">Aucune visite.</td></tr>}
          {visites.map((v) => (
            <tr key={v.id}>
              <td>{v.utilisateur.prenom} {v.utilisateur.nom}</td>
              <td>{v.annonce.titre}</td>
              <td>{new Date(v.date_visite).toLocaleString('fr-FR')}</td>
              <td><Statut valeur={v.statut} /></td>
              <td className="actions">
                {v.statut === 'en_attente' && (
                  <>
                    <button className="btn" onClick={() => traiterVisite(v.id, 'confirmee')}>Confirmer</button>
                    <button className="btn btn-danger" onClick={() => traiterVisite(v.id, 'refusee')}>Refuser</button>
                  </>
                )}
                {['en_attente', 'confirmee'].includes(v.statut) && (
                  <>
                    <button className="btn btn-clair" onClick={() => reprogrammer(v.id)}>Reprogrammer</button>
                    <button className="btn btn-clair" onClick={() => traiterVisite(v.id, 'annulee')}>Annuler</button>
                  </>
                )}
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      <h1 style={{ marginTop: 40 }}>Offres reçues</h1>
      <table className="tableau">
        <thead><tr><th>Client</th><th>Bien</th><th>Montant</th><th>Message</th><th>Statut</th><th>Actions</th></tr></thead>
        <tbody>
          {offres.length === 0 && <tr><td colSpan="6">Aucune offre.</td></tr>}
          {offres.map((o) => (
            <tr key={o.id}>
              <td>{o.utilisateur.prenom} {o.utilisateur.nom}</td>
              <td>{o.annonce.titre}</td>
              <td>{Number(o.montant).toLocaleString('fr-FR')} MAD</td>
              <td>{o.message}</td>
              <td><Statut valeur={o.statut} /></td>
              <td className="actions">
                {o.statut === 'en_attente' && (
                  <>
                    <button className="btn" onClick={() => traiterOffre(o.id, 'acceptee')}>Accepter</button>
                    <button className="btn btn-danger" onClick={() => traiterOffre(o.id, 'refusee')}>Refuser</button>
                  </>
                )}
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}