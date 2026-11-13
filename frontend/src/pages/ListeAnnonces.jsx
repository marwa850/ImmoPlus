import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import api from '../services/api';

const icones = { appartement: '🏢', villa: '🏡', maison: '🏠' };

// Valeurs de départ des filtres
const filtresVides = {
  search: '', ville: '', type_bien: '', type_transaction: '',
  prix_min: '', prix_max: '', tri: 'date', ordre: 'desc',
};

export default function ListeAnnonces() {
  const [annonces, setAnnonces] = useState([]);
  const [filtres, setFiltres] = useState(filtresVides);
  const [page, setPage] = useState(1);
  const [derniere, setDerniere] = useState(1);
  const [chargement, setChargement] = useState(true);
  const [erreur, setErreur] = useState('');

  // Demande les annonces à Laravel avec les filtres et la page
  const charger = (f, p) => {
    api.get('/annonces', { params: { ...f, page: p } })
      .then((reponse) => {
        setAnnonces(reponse.data.data);
        setDerniere(reponse.data.last_page);
      })
      .catch(() => setErreur('Impossible de charger les annonces'))
      .finally(() => setChargement(false));
  };

  // Premier affichage
  useEffect(() => {
    charger(filtresVides, 1);
  }, []);

  // Met à jour un champ de filtre
  const changer = (e) => setFiltres({ ...filtres, [e.target.name]: e.target.value });

  const lancer = (f, p) => {
    setChargement(true);
    setErreur('');
    setPage(p);
    charger(f, p);
  };

  const chercher = (e) => {
    e.preventDefault();
    lancer(filtres, 1);
  };

  const reinitialiser = () => {
    setFiltres(filtresVides);
    lancer(filtresVides, 1);
  };

  return (
    <div>
      <div className="hero">
        <h1>Trouvez la maison<br />de vos rêves</h1>
        <p>Appartements, villas et maisons à vendre ou à louer.</p>
      </div>

      <form className="recherche" onSubmit={chercher}>
        <input name="search" placeholder="Rechercher par titre, ville ou quartier..."
          value={filtres.search} onChange={changer} />
        <button className="btn" type="submit">Rechercher</button>
      </form>

      {/* ---------- Filtres ---------- */}
      <form className="filtres" onSubmit={chercher}>
        <input name="ville" placeholder="Ville" value={filtres.ville} onChange={changer} />
        <select name="type_bien" value={filtres.type_bien} onChange={changer}>
          <option value="">Tous les biens</option>
          <option value="appartement">Appartement</option>
          <option value="villa">Villa</option>
          <option value="maison">Maison</option>
        </select>
        <select name="type_transaction" value={filtres.type_transaction} onChange={changer}>
          <option value="">Vente et location</option>
          <option value="vente">Vente</option>
          <option value="location">Location</option>
        </select>
        <input name="prix_min" type="number" placeholder="Prix min" value={filtres.prix_min} onChange={changer} />
        <input name="prix_max" type="number" placeholder="Prix max" value={filtres.prix_max} onChange={changer} />
        <select name="tri" value={filtres.tri} onChange={changer}>
          <option value="date">Trier par date</option>
          <option value="prix">Trier par prix</option>
        </select>
        <select name="ordre" value={filtres.ordre} onChange={changer}>
          <option value="desc">Décroissant</option>
          <option value="asc">Croissant</option>
        </select>
        <button className="btn" type="submit">Filtrer</button>
        <button className="btn btn-clair" type="button" onClick={reinitialiser}>Réinitialiser</button>
      </form>

      <h2 className="titre-section">Nos annonces</h2>

      {erreur && <p className="erreur">{erreur}</p>}
      {chargement && <p className="vide">Chargement...</p>}
      {!chargement && annonces.length === 0 && <p className="vide">Aucune annonce trouvée.</p>}

      <div className="grille">
        {annonces.map((a) => {
          const details = a.bien.appartement || a.bien.villa || a.bien.maison;
          return (
            <Link to={`/annonces/${a.id}`} className="carte" key={a.id}>
              <div className="image">
                {icones[a.bien.type_bien]}
                <span className={`badge ${a.type_transaction}`}>{a.type_transaction}</span>
              </div>
              <div className="contenu">
                <h3>{a.titre}</h3>
                <p className="lieu">📍 {a.bien.ville}</p>
                <div className="infos">
                  <span>📐 {Math.round(a.bien.superficie)} m²</span>
                  {details && <span>🛏️ {details.chambres} ch.</span>}
                  {details && <span>🚿 {details.salles_de_bain} sdb</span>}
                </div>
                <p className="prix">
                  {Number(a.prix).toLocaleString('fr-FR')} MAD
                  {a.type_transaction === 'location' && <small> / mois</small>}
                </p>
              </div>
            </Link>
          );
        })}
      </div>

      {/* ---------- Pagination (F9) ---------- */}
      {derniere > 1 && (
        <div className="pagination">
          <button className="btn btn-clair" disabled={page <= 1}
            onClick={() => lancer(filtres, page - 1)}>← Précédent</button>
          <span>Page {page} / {derniere}</span>
          <button className="btn btn-clair" disabled={page >= derniere}
            onClick={() => lancer(filtres, page + 1)}>Suivant →</button>
        </div>
      )}
    </div>
  );
}