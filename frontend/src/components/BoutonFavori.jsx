import { useAuth } from '../context/useAuth';

// Cœur cliquable. Il est affiché seulement pour un client connecté.
export default function BoutonFavori({ id, favoris, onBasculer }) {
  const { user } = useAuth();
  if (!user || user.role.nom !== 'client') return null;

  const actif = favoris.includes(id);

  const cliquer = (e) => {
    e.preventDefault(); // la carte est un lien : on évite d'ouvrir le détail
    onBasculer(id);
  };

  return (
    <button className={`coeur ${actif ? 'actif' : ''}`} onClick={cliquer} title="Favori">
      {actif ? '❤️' : '🤍'}
    </button>
  );
}