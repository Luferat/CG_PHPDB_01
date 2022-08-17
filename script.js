/**
 * JavaScript global
 */

// Previne o reenvio de formulários ao recarregar a página:
if (window.history.replaceState)
    window.history.replaceState(null, null, window.location.href);