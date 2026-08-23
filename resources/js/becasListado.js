
document.addEventListener('DOMContentLoaded', () => {
    const tarjetas = document.querySelectorAll('.tarjeta');

    tarjetas.forEach(tarjeta => {
        tarjeta.addEventListener('click', (e) => {
            const estaExpandida = tarjeta.classList.contains('tarjetaAbierta');
            if (!estaExpandida) {
                e.preventDefault();
                tarjetas.forEach(t => t.classList.remove('tarjetaAbierta'));
                tarjeta.classList.add('tarjetaAbierta');
            } else {
                const url = tarjeta.getAttribute('data-url')
                window.location.href = url
            }
        });
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('.tarjeta')) {
            tarjetas.forEach(t => t.classList.remove('tarjetaAbierta'))
        }
    })
});


document.addEventListener('DOMContentLoaded', () => {
    const destacados = document.querySelectorAll('.destacado');

    destacados.forEach(destacado => {
        destacado.addEventListener('click', (e) => {
            const estaActivo = destacado.classList.contains('activo');
            if (!estaActivo) {
                destacados.forEach(d => d.classList.remove('activo'));
                destacado.classList.add('activo');
            }
        })
    })

    
});