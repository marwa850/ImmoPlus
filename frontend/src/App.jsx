import { Routes, Route } from 'react-router-dom';
import Navbar from './components/Navbar';
import ListeAnnonces from './pages/ListeAnnonces';

export default function App() {
  return (
    <>
      <Navbar />
      <main className="container">
        {/* Chaque Route associe une adresse à une page */}
        <Routes>
          <Route path="/" element={<ListeAnnonces />} />
        </Routes>
      </main>
    </>
  );
}