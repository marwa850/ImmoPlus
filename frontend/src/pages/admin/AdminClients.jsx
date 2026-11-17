import { useEffect, useState } from 'react';
import api from '../../services/api';

export default function AdminClients() {
  const [clients, setClients] = useState([]);
  const [edition, setEdition] = useState(null); // client en cours de modification
  const [erreur, setErreur] = useState('');

  const charger = () => api.get('/admin/clients').then((r) => setClients(r.data));

  useEffect(() => {
    charger();
  }, []);

  const changer = (e) => setEdition({ ...edition, [e.target.name]: e.target.value });

  // Enregistre les modifications (F22)
  const enregistrer = async (e) => {
    e.preventDefault();
    try {
      await api.put(`/admin/clients/${edition.id}`, edition);
      setEdition(null);
      setErreur('');
      charger();
    } catch (err) {
      const erreurs = err.response?.data?.errors;
      setErreur(erreurs ? Object.values(erreurs).flat().join(' ') : 'Erreur');
    }
  };

  // Active ou désactive le compte
  const basculer = async (id) => {
    await api.patch(`/admin/clients/${id}/actif`);
    charger();
  };

  return (
    <div className="admin">
      <h1>Gestion des clients</h1>

      {edition && (
        <form className="bloc form-client" onSubmit={enregistrer}>
          <h2>Modifier le client</h2>
          {erreur && <p className="erreur">{erreur}</p>}
          <div className="ligne">
            <input name="prenom" placeholder="Prénom" value={edition.prenom} onChange={changer} required />
            <input name="nom" placeholder="Nom" value={edition.nom} onChange={changer} required />
          </div>
          <div className="ligne">
            <input name="email" type="email" placeholder="Email" value={edition.email} onChange={changer} required />
            <input name="telephone" placeholder="Téléphone" value={edition.telephone || ''} onChange={changer} />
          </div>
          <div className="actions">
            <button className="btn" type="submit">Enregistrer</button>
            <button className="btn btn-clair" type="button" onClick={() => setEdition(null)}>Annuler</button>
          </div>
        </form>
      )}

      <table className="tableau">
        <thead>
          <tr><th>Nom</th><th>Email</th><th>Téléphone</th><th>Visites</th><th>Offres</th><th>Compte</th><th>Actions</th></tr>
        </thead>
        <tbody>
          {clients.length === 0 && <tr><td colSpan="7">Aucun client.</td></tr>}
          {clients.map((c) => (
            <tr key={c.id}>
              <td>{c.prenom} {c.nom}</td>
              <td>{c.email}</td>
              <td>{c.telephone}</td>
              <td>{c.visites_count}</td>
              <td>{c.offres_count}</td>
              <td>
                <span className={`statut-badge ${c.actif ? 'confirmee' : 'refusee'}`}>
                  {c.actif ? 'actif' : 'désactivé'}
                </span>
              </td>
              <td className="actions">
                <button className="btn btn-clair" onClick={() => setEdition(c)}>Modifier</button>
                <button className={`btn ${c.actif ? 'btn-danger' : ''}`} onClick={() => basculer(c.id)}>
                  {c.actif ? 'Désactiver' : 'Réactiver'}
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}