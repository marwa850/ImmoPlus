import { useState } from 'react';
import api from '../services/api';
import { useAuth } from '../context/useAuth';

const lireErreur = (err) =>
  err.response?.data?.message ||
  Object.values(err.response?.data?.errors || {}).flat().join(' ') ||
  'Erreur';

export default function Profil() {
  const { user, majUser } = useAuth();
  const [form, setForm] = useState({
    nom: user.nom, prenom: user.prenom, email: user.email, telephone: user.telephone || '',
  });
  const [mdp, setMdp] = useState({ ancien: '', mot_de_passe: '', mot_de_passe_confirmation: '' });
  const [retour1, setRetour1] = useState({ type: '', texte: '' });
  const [retour2, setRetour2] = useState({ type: '', texte: '' });

  const enregistrer = async (e) => {
    e.preventDefault();
    try {
      const r = await api.put('/profil', form);
      majUser(r.data);
      setRetour1({ type: 'succes', texte: 'Profil mis à jour' });
    } catch (err) {
      setRetour1({ type: 'erreur', texte: lireErreur(err) });
    }
  };

  const changerMdp = async (e) => {
    e.preventDefault();
    try {
      await api.put('/profil/mot-de-passe', mdp);
      setRetour2({ type: 'succes', texte: 'Mot de passe modifié' });
      setMdp({ ancien: '', mot_de_passe: '', mot_de_passe_confirmation: '' });
    } catch (err) {
      setRetour2({ type: 'erreur', texte: lireErreur(err) });
    }
  };

  return (
    <div className="admin">
      <h1>Mon profil</h1>
      <div className="actions-client">
        <form className="bloc form-client" onSubmit={enregistrer}>
          <h2>Mes informations</h2>
          {retour1.texte && <p className={retour1.type}>{retour1.texte}</p>}
          <input placeholder="Prénom" value={form.prenom} onChange={(e) => setForm({ ...form, prenom: e.target.value })} required />
          <input placeholder="Nom" value={form.nom} onChange={(e) => setForm({ ...form, nom: e.target.value })} required />
          <input type="email" placeholder="Email" value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })} required />
          <input placeholder="Téléphone" value={form.telephone} onChange={(e) => setForm({ ...form, telephone: e.target.value })} />
          <button className="btn" type="submit">Enregistrer</button>
        </form>

        <form className="bloc form-client" onSubmit={changerMdp}>
          <h2>Changer le mot de passe</h2>
          {retour2.texte && <p className={retour2.type}>{retour2.texte}</p>}
          <input type="password" placeholder="Ancien mot de passe" value={mdp.ancien} onChange={(e) => setMdp({ ...mdp, ancien: e.target.value })} required />
          <input type="password" placeholder="Nouveau mot de passe (6 min)" value={mdp.mot_de_passe} onChange={(e) => setMdp({ ...mdp, mot_de_passe: e.target.value })} required />
          <input type="password" placeholder="Confirmer" value={mdp.mot_de_passe_confirmation} onChange={(e) => setMdp({ ...mdp, mot_de_passe_confirmation: e.target.value })} required />
          <button className="btn" type="submit">Modifier</button>
        </form>
      </div>
    </div>
  );
}