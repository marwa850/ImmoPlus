import { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { useAuth } from '../context/useAuth';

export default function Connexion() {
  const { login } = useAuth();
  const navigate = useNavigate();
  const [email, setEmail] = useState('');
  const [motDePasse, setMotDePasse] = useState('');
  const [erreur, setErreur] = useState('');

  const envoyer = async (e) => {
    e.preventDefault();
    setErreur('');
    try {
      const user = await login(email, motDePasse);
      // L'admin va vers son espace, le client vers l'accueil
      navigate(user.role.nom === 'admin' ? '/admin/annonces' : '/');
    } catch (err) {
      setErreur(err.response?.data?.message || 'Erreur de connexion');
    }
  };

  return (
    <form className="formulaire" onSubmit={envoyer}>
      <h1>Connexion</h1>
      {erreur && <p className="erreur">{erreur}</p>}
      <input type="email" placeholder="Email" value={email}
        onChange={(e) => setEmail(e.target.value)} required />
      <input type="password" placeholder="Mot de passe" value={motDePasse}
        onChange={(e) => setMotDePasse(e.target.value)} required />
      <button type="submit">Se connecter</button>
      <p>Pas de compte ? <Link to="/inscription">S'inscrire</Link></p>
    </form>
  );
}