import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import api from '../../services/api';

const statuts = ['brouillon', 'disponible', 'reservee', 'vendue', 'louee', 'archivee'];

export default function AdminAnnonces() {
  const [annonces, setAnnonces] = useState([]);
  const [page, setPage] = useState(1);
  const [derniere, setDerniere] = useState(1);
  const [erreur, setErreur] = useState('');

  // Charge la page demandée
  const charger = (p) => {
    api.get('/admin/annonces', { params: { page: p } })
      .then((r) => {
        setAnnonces(r.data.data);
        setDerniere(r.data.last_page);
        setPage(p);
      })
      .catch(() => setErreur('Impossible de charger les annonces'));
  };

  useEffect(() => {
    charger(1);
  }, []);

  // Change le statut d'une annonce (F8)
  const changerStatut = async (id, statut) => {
    await api.patch(`/admin/annonces/${id}/statut`, { statut });
    charger(page);
  };

  // Supprime une annonce (F7)
  const supprimer = async (id) => {
    if (!window.confirm('Supprimer cette annonce ?')) return;
    await api.delete(`/admin/annonces/${id}`);
    charger(page);
  };

  return (
    <div className="admin">
      <div className="admin-haut">
        <h1>Gestion des annonces</h1>
        <Link to="/admin/annonces/nouvelle" className="btn">+ Nouvelle annonce</Link>
      </div>

      {erreur && <p className="erreur">{erreur}</p>}

      <table className="tableau">
        <thead>
          <tr><th>Titre</th><th>Ville</th><th>Type</th><th>Prix</th><th>Statut</th><th>Actions</th></tr>
        </thead>
        <tbody>
          {annonces.map((a) => (
            <tr key={a.id}>
              <td>{a.titre}</td>
              <td>{a.bien.ville}</td>
              <td>{a.bien.type_bien} / {a.type_transaction}</td>
              <td>{Number(a.prix).toLocaleString('fr-FR')} MAD</td>
              <td>
                <select value={a.statut} onChange={(e) => changerStatut(a.id, e.target.value)}>
                  {statuts.map((s) => <option key={s} value={s}>{s}</option>)}
                </select>
              </td>
              <td className="actions">
                <Link to={`/admin/annonces/${a.id}/modifier`} className="btn btn-clair">Modifier</Link>
                <button className="btn btn-danger" onClick={() => supprimer(a.id)}>Supprimer</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      {derniere > 1 && (
        <div className="pagination">
          <button className="btn btn-clair" disabled={page <= 1} onClick={() => charger(page - 1)}>← Précédent</button>
          <span>Page {page} / {derniere}</span>
          <button className="btn btn-clair" disabled={page >= derniere} onClick={() => charger(page + 1)}>Suivant →</button>
        </div>
      )}
    </div>
  );
}