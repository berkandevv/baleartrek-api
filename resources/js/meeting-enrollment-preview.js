// Convierte YYYY-MM-DD de <input type="date"> en un objeto Date local
function parseYmd(value) {
    const parts = value.split('-').map(Number);
    if (parts.length !== 3 || parts.some(Number.isNaN)) {
        return null;
    }

    return new Date(parts[0], parts[1] - 1, parts[2]);
}

// Replica Carbon::subMonthNoOverflow(): si el mes de destino tiene menos días, ajusta al último día válido
function subMonthNoOverflow(date) {
    const year = date.getFullYear();
    const monthIndex = date.getMonth();
    const day = date.getDate();

    const target = new Date(year, monthIndex, 1);
    target.setMonth(target.getMonth() - 1);

    const daysInTargetMonth = new Date(
        target.getFullYear(),
        target.getMonth() + 1,
        0
    ).getDate();

    target.setDate(Math.min(day, daysInTargetMonth));

    return target;
}

function subWeek(date) {
    const copy = new Date(date);
    copy.setDate(copy.getDate() - 7);
    return copy;
}

// Mantiene el formato de la UI alineado con los accessors del backend (dd-mm-YYYY)
function formatDate(date) {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = String(date.getFullYear());
    return `${day}-${month}-${year}`;
}

function initMeetingEnrollmentPreview() {
    const dayInput = document.getElementById('day');
    const iniPreview = document.getElementById('app-date-ini-preview');
    const endPreview = document.getElementById('app-date-end-preview');

    // Si el formulario no existe en esta página, no hace nada
    if (!dayInput || !iniPreview || !endPreview) {
        return;
    }

    function updateEnrollmentPreview() {
        const value = dayInput.value?.trim();
        // Si la fecha es inválida o vacía, evita mostrar cálculos antiguos
        if (!value) {
            iniPreview.textContent = '-';
            endPreview.textContent = '-';
            return;
        }

        const day = parseYmd(value);
        if (!day || Number.isNaN(day.getTime())) {
            iniPreview.textContent = '-';
            endPreview.textContent = '-';
            return;
        }

        iniPreview.textContent = formatDate(subMonthNoOverflow(day));
        endPreview.textContent = formatDate(subWeek(day));
    }

    dayInput.addEventListener('input', updateEnrollmentPreview);
    dayInput.addEventListener('change', updateEnrollmentPreview);
    // Renderiza valores iniciales (vista de edición o old input tras errores de validación)
    updateEnrollmentPreview();
}

document.addEventListener('DOMContentLoaded', initMeetingEnrollmentPreview);
