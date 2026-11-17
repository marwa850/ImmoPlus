import { Routes, Route } from 'react-router-dom';
import Navbar from './components/Navbar';
import RouteAdmin from './components/RouteAdmin';
import ListeAnnonces from './pages/ListeAnnonces';
import DetailAnnonce from './pages/DetailAnnonce';
import Connexion from './pages/Connexion';
import Inscription from './pages/Inscription';
import MesFavoris from './pages/MesFavoris';
import MesDemandes from './pages/MesDemandes';
import AdminAnnonces from './pages/admin/AdminAnnonces';
import FormAnnonce from './pages/admin/FormAnnonce';
import AdminDemandes from './pages/admin/AdminDemandes';
import Notifications from './pages/Notifications';
import AdminClients from './pages/admin/AdminClients';
import AdminDashboard from './pages/admin/AdminDashboard';
import DashboardClient from './pages/DashboardClient';

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

          {/* Espace client */}
          <Route path="/favoris" element={<MesFavoris />} />
          <Route path="/mes-demandes" element={<MesDemandes />} />
          <Route path="/notifications" element={<Notifications />} />

          {/* Espace administrateur (protégé) */}
          <Route path="/admin/annonces" element={<RouteAdmin><AdminAnnonces /></RouteAdmin>} />
          <Route path="/admin/annonces/nouvelle" element={<RouteAdmin><FormAnnonce /></RouteAdmin>} />
          <Route path="/admin/annonces/:id/modifier" element={<RouteAdmin><FormAnnonce /></RouteAdmin>} />
          <Route path="/admin/demandes" element={<RouteAdmin><AdminDemandes /></RouteAdmin>} />
          <Route path="/admin/clients" element={<RouteAdmin><AdminClients /></RouteAdmin>} />
          <Route path="/tableau-de-bord" element={<DashboardClient />} />
          <Route path="/admin" element={<RouteAdmin><AdminDashboard /></RouteAdmin>} />
        </Routes>
      </main>
    </>
  );
}