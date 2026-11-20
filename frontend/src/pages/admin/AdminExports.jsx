import { useState } from 'react';
import api from '../../services/api';

export default function AdminExports() {
  const [mode, setMode] = useState('jour');
  const [date, setDate] = useState(new Date().toISOString().slice(0, 10));
  const [erreur, setErreur] = useState('');

  // Télécharge un fichier depuis l'API en envoyant le token
  const telecharger = async (url, nomFichier, params = {}) => {
    setErreur('');
    try {
      const r = await api.get(url, { params, responseType: 'blob' });
      const lien = document.createElement('a');
      lien.href = URL.createObjectURL(r.data);
      lien.download = nomFichier;
      lien.click();
      URL.revokeObjectURL(lien.href);
    } catch {
      setErreur("Le téléchargement a échoué.");
    }
  };

  return (
    <div className="admin">
      <h1>Exports</h1>
      {erreur && <p className="erreur">{erreur}</p>}

      <div className="actions-client">
        <div className="bloc">
          <h2>📊 Excel</h2>
          <p>Liste complète des annonces ou des offres.</p>
          <div className="actions">
            <button className="btn" onClick={() => telecharger('/admin/export/annonces', 'annonces.xlsx')}>Annonces (.xlsx)</button>
            <button className="btn" onClick={() => telecharger('/admin/export/offres', 'offres.xlsx')}>Offres (.xlsx)</button>
          </div>
        </div>

        <div className="bloc">
          <h2>📄 PDF : statistiques</h2>
          <p>Chiffres clés, totaux et évolution mensuelle.</p>
          <button className="btn" onClick={() => telecharger('/admin/export/stats', 'rapport-statistiques.pdf')}>Rapport (.pdf)</button>
        </div>
      </div>

      <div className="bloc" style={{ marginTop: 20 }}>
        <h2>📄 PDF : planning des visites</h2>
        <div className="filtres" style={{ border: 'none', padding: 0 }}>
          <select value={mode} onChange={(e) => setMode(e.target.value)}>
            <option value="jour">Par jour</option>
            <option value="semaine">Par semaine</option>
          </select>
          <input type="date" value={date} onChange={(e) => setDate(e.target.value)} />
          <button className="btn" onClick={() => telecharger('/admin/export/visites', 'planning-visites.pdf', { mode, date })}>
            Télécharger le planning
          </button>
        </div>
      </div>
    </div>
  );
}