import { useEffect, useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import api from '../../services/api';

const vide = {
  titre: '', type_transaction: 'vente', prix: '', type_bien: 'appartement',
  adresse: '', ville: '', superficie: '', description: '',
  chambres: 0, salles_de_bain: 0, etage: '', nombre_etages: 1,
  ascenseur: false, balcon: false, piscine: false, jardin: false, garage: false,
};

export default function FormAnnonce() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [form, setForm] = useState(vide);
  const [erreur, setErreur] = useState('');

  // En modification : on charge l'annonce existante
  useEffect(() => {
    if (!id) return;
    api.get(`/annonces/${id}`).then((r) => {
      const a = r.data;
      const d = a.bien.appartement || a.bien.villa || a.bien.maison || {};
      setForm({
        ...vide, ...d,
        titre: a.titre, type_transaction: a.type_transaction, prix: a.prix,
        type_bien: a.bien.type_bien, adresse: a.bien.adresse, ville: a.bien.ville,
        superficie: a.bien.superficie, description: a.bien.description || '',
        etage: d.etage ?? '',
      });
    });
  }, [id]);

  const changer = (e) => {
    const { name, type, checked, value } = e.target;
    setForm({ ...form, [name]: type === 'checkbox' ? checked : value });
  };

  const envoyer = async (e) => {
    e.preventDefault();
    setErreur('');
    try {
      if (id) await api.put(`/admin/annonces/${id}`, form);
      else await api.post('/admin/annonces', form);
      navigate('/admin/annonces');
    } catch (err) {
      const erreurs = err.response?.data?.errors;
      setErreur(erreurs ? Object.values(erreurs).flat().join(' ') : 'Erreur d\'enregistrement');
    }
  };

  const t = form.type_bien;

  return (
    <form className="formulaire large" onSubmit={envoyer}>
      <h1>{id ? 'Modifier l\'annonce' : 'Nouvelle annonce'}</h1>
      {erreur && <p className="erreur">{erreur}</p>}

      <input name="titre" placeholder="Titre" value={form.titre} onChange={changer} required />

      <div className="ligne">
        <select name="type_bien" value={form.type_bien} onChange={changer} disabled={!!id}>
          <option value="appartement">Appartement</option>
          <option value="villa">Villa</option>
          <option value="maison">Maison</option>
        </select>
        <select name="type_transaction" value={form.type_transaction} onChange={changer}>
          <option value="vente">Vente</option>
          <option value="location">Location</option>
        </select>
      </div>

      <div className="ligne">
        <input name="prix" type="number" placeholder="Prix (MAD)" value={form.prix} onChange={changer} required />
        <input name="superficie" type="number" placeholder="Superficie (m²)" value={form.superficie} onChange={changer} required />
      </div>

      <div className="ligne">
        <input name="ville" placeholder="Ville" value={form.ville} onChange={changer} required />
        <input name="adresse" placeholder="Adresse / quartier" value={form.adresse} onChange={changer} required />
      </div>

      <div className="ligne">
        <input name="chambres" type="number" min="0" placeholder="Chambres" value={form.chambres} onChange={changer} />
        <input name="salles_de_bain" type="number" min="0" placeholder="Salles de bain" value={form.salles_de_bain} onChange={changer} />
      </div>

      {/* Champs propres à chaque type de bien */}
      {t === 'appartement' && (
        <>
          <input name="etage" type="number" placeholder="Étage" value={form.etage} onChange={changer} />
          <div className="cases">
            <label><input type="checkbox" name="ascenseur" checked={form.ascenseur} onChange={changer} /> Ascenseur</label>
            <label><input type="checkbox" name="balcon" checked={form.balcon} onChange={changer} /> Balcon</label>
          </div>
        </>
      )}
      {t !== 'appartement' && (
        <>
          <input name="nombre_etages" type="number" min="1" placeholder="Nombre d'étages" value={form.nombre_etages} onChange={changer} />
          <div className="cases">
            {t === 'villa' && <label><input type="checkbox" name="piscine" checked={form.piscine} onChange={changer} /> Piscine</label>}
            <label><input type="checkbox" name="jardin" checked={form.jardin} onChange={changer} /> Jardin</label>
            <label><input type="checkbox" name="garage" checked={form.garage} onChange={changer} /> Garage</label>
          </div>
        </>
      )}

      <textarea name="description" rows="4" placeholder="Description" value={form.description} onChange={changer} />

      <button type="submit">{id ? 'Enregistrer' : 'Créer l\'annonce'}</button>
    </form>
  );
}