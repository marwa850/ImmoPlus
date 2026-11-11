import { useEffect, useState } from 'react';
import api from '../services/api';

export default function ListeAnnonces() {
  const [annonces, setAnnonces] = useState([]);
  const [chargement, setChargement] = useState(true);
  const [erreur, setErreur] = useState('');

  // Au chargement de la page, on demande les annonces à Laravel
  useEffect(() => {
    api.get('/annonces')
      .then((reponse) => setAnnonces(reponse.data.data))
      .catch(() => setErreur('Impossible de charger les annonces'))
      .finally(() => setChargement(false));
  }, []);

  if (chargement) return <p>Chargement...</p>;
  if (erreur) return <p className="erreur">{erreur}</p>;

  return (
    <div>
      <h1>Nos annonces</h1>
      <div className="grille">
        {annonces.map((a) => (
          <div className="carte" key={a.id}>
            <h3>{a.titre}</h3>
            <p>{a.bien.ville} - {a.bien.superficie} m²</p>
            <p className="prix">{Number(a.prix).toLocaleString('fr-FR')} MAD</p>
            <span className="badge">{a.type_transaction}</span>
          </div>
        ))}
      </div>
    </div>
  );
}