import { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { useAuth } from '../context/useAuth';

export default function Inscription() {
  const { register } = useAuth();
  const navigate = useNavigate();
  const [form, setForm] = useState({
    nom: '', prenom: '', email: '', telephone: '',
    mot_de_passe: '', mot_de_passe_confirmation: '',
  });
  const [erreur, setErreur] = useState('');

  // Met à jour le champ modifié
  const changer = (e) => setForm({ ...form, [e.target.name]: e.target.value });

  const envoyer = async (e) => {
    e.preventDefault();
    setErreur('');
    try {
      await register(form);
      navigate('/');
    } catch (err) {
      // Laravel renvoie les erreurs de validation dans err.response.data.errors
      const erreurs = err.response?.data?.errors;
      setErreur(erreurs ? Object.values(erreurs).flat().join(' ') : "Erreur d'inscription");
    }
  };

  return (
    <form className="formulaire" onSubmit={envoyer}>
      <h1>Inscription</h1>
      {erreur && <p className="erreur">{erreur}</p>}
      <input name="nom" placeholder="Nom" value={form.nom} onChange={changer} required />
      <input name="prenom" placeholder="Prénom" value={form.prenom} onChange={changer} required />
      <input name="email" type="email" placeholder="Email" value={form.email} onChange={changer} required />
      <input name="telephone" placeholder="Téléphone" value={form.telephone} onChange={changer} />
      <input name="mot_de_passe" type="password" placeholder="Mot de passe (6 caractères min)"
        value={form.mot_de_passe} onChange={changer} required />
      <input name="mot_de_passe_confirmation" type="password" placeholder="Confirmer le mot de passe"
        value={form.mot_de_passe_confirmation} onChange={changer} required />
      <button type="submit">Créer mon compte</button>
      <p>Déjà inscrit ? <Link to="/connexion">Se connecter</Link></p>
    </form>
  );
}