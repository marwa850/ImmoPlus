import { useState } from 'react';

export default function Contact() {
  const [envoye, setEnvoye] = useState(false);

  const envoyer = (e) => {
    e.preventDefault();
    setEnvoye(true);
  };

  return (
    <div className="admin">
      <div className="hero">
        <h1>À propos d'ImmoPlus</h1>
        <p>Une agence immobilière en ligne pour acheter, vendre ou louer simplement.</p>
      </div>

      <div className="actions-client">
        <div className="bloc">
          <h2>Qui sommes-nous ?</h2>
          <p>ImmoPlus publie des appartements, villas et maisons. Les clients consultent les annonces, demandent des visites et envoient des offres, avec un suivi clair de chaque demande.</p>
          <p>📍 Agadir, Maroc<br />📞 0600 000 000<br />✉️ contact@immoplus.com</p>
        </div>

        <form className="bloc form-client" onSubmit={envoyer}>
          <h2>Nous contacter</h2>
          {envoye && <p className="succes">Merci, votre message a bien été envoyé.</p>}
          <input placeholder="Votre nom" required />
          <input type="email" placeholder="Votre email" required />
          <textarea rows="4" placeholder="Votre message" required />
          <button className="btn" type="submit">Envoyer</button>
        </form>
      </div>
    </div>
  );
}