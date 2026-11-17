// Graphique en barres : une colonne par mois
export default function GraphiqueMensuel({ donnees, champ, couleur }) {
  const max = Math.max(...donnees.map((d) => d[champ]), 1); // évite la division par 0

  return (
    <div className="graphique">
      {donnees.map((d) => (
        <div className="colonne" key={d.mois} title={Number(d[champ]).toLocaleString('fr-FR') + ' MAD'}>
          <span className="valeur">{d[champ] > 0 ? Math.round(d[champ] / 1000) + 'k' : ''}</span>
          <div className="barre" style={{ height: (d[champ] / max) * 100 + '%', background: couleur }} />
          <small>{d.mois.slice(5)}/{d.mois.slice(2, 4)}</small>
        </div>
      ))}
    </div>
  );
}