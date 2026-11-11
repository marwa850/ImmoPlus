import { Routes, Route } from 'react-router-dom';
import Navbar from './components/Navbar';
import ListeAnnonces from './pages/ListeAnnonces';
import Connexion from './pages/Connexion';
import Inscription from './pages/Inscription';

export default function App() {
  return (
    <>
      <Navbar />
      <main className="container">
        <Routes>
          <Route path="/" element={<ListeAnnonces />} />
          <Route path="/connexion" element={<Connexion />} />
          <Route path="/inscription" element={<Inscription />} />
        </Routes>
      </main>
    </>
  );
}