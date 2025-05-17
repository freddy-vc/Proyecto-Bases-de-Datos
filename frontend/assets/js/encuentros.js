document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('partidos-container');
    fetch('../backend/api/encuentros.php')
        .then(response => response.json())
        .then(data => {
            container.innerHTML = '';
            if (data.length === 0) {
                container.innerHTML = '<div class="loading-message">No hay partidos programados.</div>';
                return;
            }
            data.forEach(partido => {
                const card = document.createElement('div');
                card.className = 'partido-card';
                card.innerHTML = `
                    <div class="partido-equipos">${partido.equipo_local} vs ${partido.equipo_visitante}</div>
                    <div class="partido-fecha"><strong>Fecha:</strong> ${partido.fecha}</div>
                    <div class="partido-hora"><strong>Hora:</strong> ${partido.hora}</div>
                    <div class="partido-lugar"><strong>Lugar:</strong> ${partido.lugar}</div>
                `;
                container.appendChild(card);
            });
        })
        .catch(error => {
            container.innerHTML = '<div class="loading-message">Error al cargar los partidos.</div>';
        });
});