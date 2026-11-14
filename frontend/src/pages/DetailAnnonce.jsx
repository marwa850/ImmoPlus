import { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import api from '../services/api';
import { useAuth } from '../context/useAuth';

const icones = { appartement: '🏢', villa: '🏡', maison: '🏠' };

// Transforme une erreur Laravel en texte lisible
const messageErreur = (err) =>
  err.response?.data?.message ||
  Object.values(err.response?.data?.errors || {}).flat().join(' ') ||
  'Une erreur est survenue';

export default function DetailAnnonce() {
  const { id } = useParams(); // le numéro dans l'adresse /annonces/:id
  const { user } = useAuth();

  const [annonce, setAnnonce] = useState(null);
  const [erreur, setErreur] = useState('');

  // Formulaires du client
  const [dateVisite, setDateVisite] = useState('');
  const [montant, setMontant] = useState('');
  const [message, setMessage] = useState('');
  const [retour, setRetour] = useState({ type: '', texte: '' });

  useEffect(() => {
    api.get(`/annonces/${id}`)
      .then((reponse) => setAnnonce(reponse.data))
      .catch(() => setErreur('Annonce introuvable'));
  }, [id]);

  // Demander une visite (F15)
  const demanderVisite = async (e) => {
    e.preventDefault();
    try {
      await api.post('/visites', { annonce_id: id, date_visite: dateVisite });
      setRetour({ type: 'succes', texte: 'Demande de visite envoyée !' });
      setDateVisite('');
    } catch (err) {
      setRetour({ type: 'erreur', texte: messageErreur(err) });
    }
  };

  // Envoyer une offre (F16)
  const envoyerOffre = async (e) => {
    e.preventDefault();
    try {
      await api.post('/offres', { annonce_id: id, montant, message });
      setRetour({ type: 'succes', texte: 'Offre envoyée !' });
      setMontant('');
      setMessage('');
    } catch (err) {
      setRetour({ type: 'erreur', texte: messageErreur(err) });
    }
  };

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
    ["🏗️ Nombre d'étages", d.nombre_etages],
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

      {/* ---------- Actions du client ---------- */}
      {retour.texte && <p className={retour.type}>{retour.texte}</p>}

      {!user && (
        <div className="bloc">
          <p><Link to="/connexion">Connectez-vous</Link> pour demander une visite ou faire une offre.</p>
        </div>
      )}

      {user?.role.nom === 'client' && annonce.statut === 'disponible' && (
        <div className="actions-client">
          <form className="bloc" onSubmit={demanderVisite}>
            <h2>📅 Demander une visite</h2>
            <input type="datetime-local" value={dateVisite}
              onChange={(e) => setDateVisite(e.target.value)} required />
            <button className="btn" type="submit">Demander la visite</button>
          </form>

          <form className="bloc" onSubmit={envoyerOffre}>
            <h2>💰 Faire une offre</h2>
            <input type="number" placeholder="Montant (MAD)" value={montant}
              onChange={(e) => setMontant(e.target.value)} required />
            <textarea rows="2" placeholder="Message (facultatif)" value={message}
              onChange={(e) => setMessage(e.target.value)} />
            <button className="btn" type="submit">Envoyer l'offre</button>
          </form>
        </div>
      )}
    </div>
  );
}