import { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import api from '../services/api';

const icones = { appartement: '🏢', villa: '🏡', maison: '🏠' };

export default function DetailAnnonce() {
  const { id } = useParams(); // le numéro dans l'adresse /annonces/:id
  const [annonce, setAnnonce] = useState(null);
  const [erreur, setErreur] = useState('');

  useEffect(() => {
    api.get(`/annonces/${id}`)
      .then((reponse) => setAnnonce(reponse.data))
      .catch(() => setErreur('Annonce introuvable'));
  }, [id]);

  if (erreur) return <p className="erreur">{erreur}</p>;
  if (!annonce) return <p className="vide">Chargement...</p>;

  const bien = annonce.bien;
  const d = bien.appartement || bien.villa || bien.maison || {};

  // Liste des caractéristiques à afficher (seulement celles qui existent)
  const caracteristiques = [
    ['📐 Superficie', `${Math.round(bien.superficie)} m²`],
    ['🛏️ Chambres', d.chambres],
    ['🚿 Salles de bain', d.salles_de_bain],
    ['🏢 Étage', d.etage],
    ['🏗️ Nombre d\'étages', d.nombre_etages],
    ['🛗 Ascenseur', d.ascenseur ? 'Oui' : null],
    ['🌇 Balcon', d.balcon ? 'Oui' : null],
    ['🏊 Piscine', d.piscine ? 'Oui' : null],
    ['🌳 Jardin', d.jardin ? 'Oui' : null],
    ['🚗 Garage', d.garage ? 'Oui' : null],
  ].filter(([, valeur]) => valeur !== undefined && valeur !== null);

  return (
    <div className="detail">
      <Link to="/" className="retour">← Retour aux annonces</Link>

      <div className="detail-haut">
        <div className="detail-image">
          {icones[bien.type_bien]}
          <span className={`badge ${annonce.type_transaction}`}>{annonce.type_transaction}</span>
        </div>

        <div className="detail-infos">
          <h1>{annonce.titre}</h1>
          <p className="lieu">📍 {bien.adresse}, {bien.ville}</p>
          <p className="prix grand">
            {Number(annonce.prix).toLocaleString('fr-FR')} MAD
            {annonce.type_transaction === 'location' && <small> / mois</small>}
          </p>
          <p className="statut">Statut : <strong>{annonce.statut}</strong></p>

          {/* Les boutons visite et offre seront ajoutés à l'étape suivante */}
        </div>
      </div>

      <div className="bloc">
        <h2>Caractéristiques</h2>
        <div className="caracteristiques">
          {caracteristiques.map(([nom, valeur]) => (
            <div key={nom}><span>{nom}</span><strong>{valeur}</strong></div>
          ))}
        </div>
      </div>

      {bien.description && (
        <div className="bloc">
          <h2>Description</h2>
          <p>{bien.description}</p>
        </div>
      )}
    </div>
  );
}