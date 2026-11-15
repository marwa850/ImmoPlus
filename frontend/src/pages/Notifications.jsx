import { useEffect, useState } from 'react';
import api from '../services/api';

const icones = { visite: '📅', offre: '💰' };

export default function Notifications() {
  const [notifs, setNotifs] = useState([]);

  const charger = () => api.get('/notifications').then((r) => setNotifs(r.data));

  useEffect(() => {
    charger();
  }, []);

  const lire = async (n) => {
    if (n.lu) return;
    await api.patch(`/notifications/${n.id}/lue`);
    charger();
  };

  const toutLire = async () => {
    await api.patch('/notifications/tout-lire');
    charger();
  };

  return (
    <div className="admin">
      <div className="admin-haut">
        <h1>Notifications</h1>
        <button className="btn btn-clair" onClick={toutLire}>Tout marquer comme lu</button>
      </div>

      {notifs.length === 0 && <p className="vide">Aucune notification.</p>}

      <div className="liste-notifs">
        {notifs.map((n) => (
          <div key={n.id} className={`notif ${n.lu ? '' : 'non-lue'}`} onClick={() => lire(n)}>
            <span className="notif-icone">{icones[n.type] || '🔔'}</span>
            <div>
              <p>{n.message}</p>
              <small>{new Date(n.date_creation).toLocaleString('fr-FR')}</small>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}