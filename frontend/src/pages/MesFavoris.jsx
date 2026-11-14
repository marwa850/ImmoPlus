import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import api from '../services/api';

const icones = { appartement: '🏢', villa: '🏡', maison: '🏠' };

export default function MesFavoris() {
  const [annonces, setAnnonces] = useState([]);

  useEffect(() => {
    api.get('/favoris').then((r) => setAnnonces(r.data));
  }, []);

  // Retire un favori puis met la liste à jour
  const retirer = async (e, id) => {
    e.preventDefault();
    await api.post(`/favoris/${id}`);
    setAnnonces(annonces.filter((a) => a.id !== id));
  };

  return (
    <div className="admin">
      <h1>Mes favoris</h1>
      {annonces.length === 0 && <p className="vide">Aucun favori pour le moment.</p>}

      <div className="grille">
        {annonces.map((a) => (
          <Link to={`/annonces/${a.id}`} className="carte" key={a.id}>
            <div className="image">
              {icones[a.bien.type_bien]}
              <span className={`badge ${a.type_transaction}`}>{a.type_transaction}</span>
              <button className="coeur actif" onClick={(e) => retirer(e, a.id)}>❤️</button>
            </div>
            <div className="contenu">
              <h3>{a.titre}</h3>
              <p className="lieu">📍 {a.bien.ville}</p>
              <p className="prix">{Number(a.prix).toLocaleString('fr-FR')} MAD</p>
            </div>
          </Link>
        ))}
      </div>
    </div>
  );
}