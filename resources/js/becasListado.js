/**
 * becasListado.js
 * Inicialización de las tarjetas de becas — solo animaciones visuales.
 * NO intercepta clicks en botones de acción (postular, guardar, chat).
 *
 * Las funciones interactivas (postularBeca, toggleGuardar, abrirChatBeca)
 * se definen en el @push('scripts') de cada vista blade para tener acceso
 * a los valores de Blade/PHP (CSRF, rutas, traducciones, etc.)
 */

document.addEventListener('DOMContentLoaded', () => {
    // Animación de entrada de tarjetas con Intersection Observer
    const tarjetas = document.querySelectorAll('.tarjeta');
    if (tarjetas.length === 0) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                // Escalonar la animación por índice
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, i * 60);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    tarjetas.forEach(tarjeta => {
        // Estado inicial para la animación
        tarjeta.style.opacity = '0';
        tarjeta.style.transform = 'translateY(20px)';
        tarjeta.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        observer.observe(tarjeta);
    });
});