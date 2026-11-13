import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import api from '../services/api';

// Une icône par type de bien
const icones = { appartement: '🏢', villa: '🏡', maison: '🏠' };

export default function ListeAnnonces() {
  const [annonces, setAnnonces] = useState([]);
  const [recherche, setRecherche] = useState('');
  const [chargement, setChargement] = useState(true);
  const [erreur, setErreur] = useState('');

  // Demande les annonces à Laravel (avec le mot recherché)
  const charger = (mot) => {
    api.get('/annonces', { params: { search: mot } })
      .then((reponse) => setAnnonces(reponse.data.data))
      .catch(() => setErreur('Impossible de charger les annonces'))
      .finally(() => setChargement(false));
  };

  // Au premier affichage, on charge toutes les annonces
  useEffect(() => {
    charger('');
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  // Quand on clique sur « Rechercher »
  const chercher = (e) => {
    e.preventDefault();
    setChargement(true);
    setErreur('');
    charger(recherche);
  };

  return (
    <div>
      <div className="hero">
        <h1>Trouvez la maison<br />de vos rêves</h1>
        <p>Appartements, villas et maisons à vendre ou à louer.</p>
      </div>

      <form className="recherche" onSubmit={chercher}>
        <input
          placeholder="Rechercher par titre, ville ou quartier..."
          value={recherche}
          onChange={(e) => setRecherche(e.target.value)}
        />
        <button className="btn" type="submit">Rechercher</button>
      </form>

      <h2 className="titre-section">Nos annonces</h2>

      {erreur && <p className="erreur">{erreur}</p>}
      {chargement && <p className="vide">Chargement...</p>}
      {!chargement && annonces.length === 0 && <p className="vide">Aucune annonce trouvée.</p>}

      <div className="grille">
        {annonces.map((a) => {
          // Les détails sont dans appartement, villa ou maison selon le type
          const details = a.bien.appartement || a.bien.villa || a.bien.maison;
          return (
            // La carte entière est un lien vers la page détail
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
    </div>
  );
}