import { Routes, Route } from 'react-router-dom';
import Navbar from './components/Navbar';
import RouteAdmin from './components/RouteAdmin';
import ListeAnnonces from './pages/ListeAnnonces';
import DetailAnnonce from './pages/DetailAnnonce';
import Connexion from './pages/Connexion';
import Inscription from './pages/Inscription';
import AdminAnnonces from './pages/admin/AdminAnnonces';
import FormAnnonce from './pages/admin/FormAnnonce';

export default function App() {
  return (
    <>
      <Navbar />
      <main className="container">
        <Routes>
          <Route path="/" element={<ListeAnnonces />} />
          <Route path="/annonces/:id" element={<DetailAnnonce />} />
          <Route path="/connexion" element={<Connexion />} />
          <Route path="/inscription" element={<Inscription />} />

          {/* Espace administrateur (protégé) */}
          <Route path="/admin/annonces" element={<RouteAdmin><AdminAnnonces /></RouteAdmin>} />
          <Route path="/admin/annonces/nouvelle" element={<RouteAdmin><FormAnnonce /></RouteAdmin>} />
          <Route path="/admin/annonces/:id/modifier" element={<RouteAdmin><FormAnnonce /></RouteAdmin>} />
        </Routes>
      </main>
    </>
  );
}